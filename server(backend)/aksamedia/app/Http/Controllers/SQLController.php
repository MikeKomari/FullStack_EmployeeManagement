<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SQLController extends Controller
{

  public function getNilaiRT()
    {
        $data = DB::select("
                    SELECT 
                        nama,
                        nisn,
                        SUM(CASE WHEN nama_pelajaran = 'Realistic' THEN skor ELSE 0 END) AS realistic,
                        SUM(CASE WHEN nama_pelajaran = 'Investigative' THEN skor ELSE 0 END) AS investigative,
                        SUM(CASE WHEN nama_pelajaran = 'Artistic' THEN skor ELSE 0 END) AS artistic,
                        SUM(CASE WHEN nama_pelajaran = 'Social' THEN skor ELSE 0 END) AS social,
                        SUM(CASE WHEN nama_pelajaran = 'Enterprising' THEN skor ELSE 0 END) AS enterprising,
                        SUM(CASE WHEN nama_pelajaran = 'Conventional' THEN skor ELSE 0 END) AS conventional
                    FROM nilai
                    WHERE materi_uji_id = 7
                    GROUP BY nama, nisn
                ");

        return response()->json($data);
    }

    public function getNilaiST()
    {
        $data = DB::select("
            SELECT 
                nama,
                nisn,
                SUM(CASE 
                    WHEN pelajaran_id = 44 THEN skor * 41.67
                    WHEN pelajaran_id = 45 THEN skor * 29.67
                    WHEN pelajaran_id = 46 THEN skor * 100
                    WHEN pelajaran_id = 47 THEN skor * 23.81
                    ELSE 0
                END) AS total,
                SUM(CASE WHEN pelajaran_id = 44 THEN skor * 41.67 ELSE 0 END) AS verbal,
                SUM(CASE WHEN pelajaran_id = 45 THEN skor * 29.67 ELSE 0 END) AS kuantitatif,
                SUM(CASE WHEN pelajaran_id = 46 THEN skor * 100 ELSE 0 END) AS penalaran,
                SUM(CASE WHEN pelajaran_id = 47 THEN skor * 23.81 ELSE 0 END) AS figural
            FROM nilai
            WHERE materi_uji_id = 4
            GROUP BY nama, nisn
            ORDER BY total DESC
        ");

        return response()->json($data);
    }
}
