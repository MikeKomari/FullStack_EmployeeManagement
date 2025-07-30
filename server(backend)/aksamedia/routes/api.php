<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\SQLController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Tugas 1
Route::post('/login', [AuthController::class, 'login']);

// Tugas 2
Route::middleware(['auth:sanctum', 'admin.only'])->group(function () {
    Route::get('/divisions', [\App\Http\Controllers\DivisionController::class, 'getDivisions']);
    
    // Tugas 3-6
    Route::prefix('employees')->group(function () {
        Route::get('/', [EmployeeController::class, 'getEmployees']);
        Route::post('/create', [EmployeeController::class, 'addEmployee']);
        Route::put('/update/{id}', [EmployeeController::class, 'updateEmployee']);
        Route::delete('/delete/{id}', [EmployeeController::class, 'deleteEmployee']);
    });
});

// Tugas 7
Route::post('/logout', [AuthController::class, 'logout']);

// Tugas SQL Tambahan

Route::get('/nilaiRT', [SQLController::class, 'getNilaiRT']);
Route::get('/nilaiST', [SQLController::class, 'getNilaiST']);