<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaAbsensiController extends Controller
{
    public function store(Request $request)
    {
        $siswa = Auth::guard('siswa')->user();

        Absensi::create([
            'siswa_id' => $siswa->id,
            'kode_barcode' => $request->barcode,
            'tanggal' => now()->toDateString(),
            'waktu' => now()->toTimeString(),
        ]);

        return response()->json(['message' => 'Absensi berhasil!']);
    }
}
