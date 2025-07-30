<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class EmployeeController extends Controller
{

    public function getEmployees(Request $request){
        $query = User::with('division')->whereNotNull('division_id');

        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->division_id) {
            $query->where('division_id', $request->division_id);
        }

        $employees = $query->paginate(10);

        return response()->json([
            'status' => 'success',
            'message' => 'Data karyawan berhasil diambil',
            'data' => [
                'employees' => $employees->map(function ($employee) {
                    return [
                        'id' => $employee->id,
                        'image' => $employee->image,
                        'name' => $employee->name,
                        'phone' => $employee->phone,
                        'division' => [
                            'id' => $employee->division->id ?? null,
                            'name' => $employee->division->name ?? null,
                        ],
                        'position' => $employee->position,
                    ];
                }),
            ],
            'pagination' => [
                'current_page' => $employees->currentPage(),
                'last_page' => $employees->lastPage(),
                'per_page' => $employees->perPage(),
                'total' => $employees->total(),
            ],
        ]);
    }

    public function addEmployee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'nullable|image|max:2048',
            'name' => 'required|string',
            'phone' => 'required|string',
            'division' => 'required|exists:divisions,id',
            'position' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 400);
        }

        // $request->validate([
        //     'image' => 'nullable|image|max:2048',
        //     'name' => 'required|string',
        //     'phone' => 'required|string',
        //     'division' => 'required|exists:divisions,id',
        //     'position' => 'required|string',
        // ],[
        //     "name.required" => "Name is required",
        //     "phone.required" => "Phone is required",
        //     "division.required" => "Division is required",
        //     "position" => "Position is required",
        //     "division.exists" => "Division does not exist",
        // ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('employees', 'public');
        }

        User::create([
            'id' => (string) Str::uuid(),
            'name' => $request->name,
            'username' => Str::slug($request->name),
            'email' => Str::slug($request->name) . rand(1000,9999) . '@example.com',
            'phone' => $request->phone,
            'division_id' => $request->division,
            'position' => $request->position,
            'image' => $imagePath ? asset('storage/' . $imagePath) : null,
            'password' => bcrypt('default123'), 
        ]);

        return response()->json([
            'status' => 'success', 
            'message' => 'Karyawan berhasil ditambahkan'
        ]);
    }

    public function updateEmployee(Request $request, $id)
    {
        $employee = User::where('id', $id)->whereNotNull('division_id')->first();

        if (!$employee) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Karyawan tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'image' => 'nullable|image|max:2048',
            'name' => 'required|string',
            'phone' => 'required|string',
            'division' => 'required|exists:divisions,id',
            'position' => 'required|string',
        ],[
            "name.required" => "Mohon Isi Nama Karyawan",
            "phone.required" => "Mohon Isi Nomor Telepon Karyawan",
            "division.required" => "Mohon Pilih Divisi Karyawan",
            "position.required" => "Mohon Isi Jabatan Karyawan",
            "division.exists" => "Divisi tidak ditemukan",
        ]);

        // if ($validator->fails()) {
        //     return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 400);
        // }

        if ($request->hasFile('image')) {
            if ($employee->image) {
                $oldPath = str_replace(asset('storage/'), '', $employee->image);
                Storage::disk('public')->delete($oldPath);
            }
            $imagePath = $request->file('image')->store('employees', 'public');
            $employee->image = asset('storage/' . $imagePath);
        }

        $employee->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'division_id' => $request->division,
            'position' => $request->position,
        ]);

        return response()->json([
            'status' => 'success', 
            'message' => 'Karyawan berhasil diperbarui'
        ]);
    }

    public function deleteEmployee($id)
    {
        $employee = User::where('id', $id)->whereNotNull('division_id')->first();

        if (!$employee) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Karyawan tidak ditemukan'
            ], 404);
        }

        if ($employee->image) {
            $oldPath = str_replace(asset('storage/'), '', $employee->image);
            Storage::disk('public')->delete($oldPath);
        }

        $employee->delete();

        return response()->json([
            'status' => 'success', 
            'message' => 'Karyawan berhasil dihapus'
        ]);
    }

    // public function getEmployee(Request $request)
    // {
    //     $query = User::with('division')->whereNotNull('division_id');

    //     $request->validate([
    //         'name' => 'required',
    //         'division_id' => 'required',
    //     ],[
    //         "name.required" => "Name is required",
    //         "division_id.required" => "Division is required",
    //     ]);

    //     $employee = User::findOrFail($request->name);

    //     $employees = $query->paginate(10);

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Data karyawan berhasil diambil',
    //         'data' => [
    //             'employees' => $employees->map(function ($employee) {
    //                 return [
    //                     'id' => $employee->id,
    //                     'image' => $employee->image,
    //                     'name' => $employee->name,
    //                     'phone' => $employee->phone,
    //                     'division' => [
    //                         'id' => $employee->division->id ?? null,
    //                         'name' => $employee->division->name ?? null,
    //                     ],
    //                     'position' => $employee->position,
    //                 ];
    //             }),
    //         ],
    //         'pagination' => [
    //             'current_page' => $employees->currentPage(),
    //             'last_page' => $employees->lastPage(),
    //             'per_page' => $employees->perPage(),
    //             'total' => $employees->total(),
    //         ],
    //     ]);
    // }
}
