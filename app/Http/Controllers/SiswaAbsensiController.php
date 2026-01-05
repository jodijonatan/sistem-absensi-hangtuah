<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaAbsensiController extends Controller
{
    // Koordinat Pusat Sekolah (Admin harus setting ini)
    private const SCHOOL_LATITUDE = -6.200000; // Ganti dengan Lat Sekolah sebenarnya
    private const SCHOOL_LONGITUDE = 106.800000; // Ganti dengan Lon Sekolah sebenarnya
    private const MAX_DISTANCE_KM = 0.5; // Maksimal 500 meter dari pusat sekolah

    // Waktu Patokan
    private const TIME_IN_LIMIT = '07:00:00'; // Batas Waktu Masuk
    private const TIME_OUT_LIMIT = '15:00:00'; // Batas Waktu Pulang Awal

    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'barcode' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $siswa = Auth::guard('siswa')->user();
        if (!$siswa) {
            return response()->json(['message' => 'Siswa tidak terautentikasi.', 'status' => 'error'], 401);
        }

        $barcode = $request->barcode;
        $now = now();
        $today = $now->toDateString();

        // 2. Geofencing Check
        $distance = $this->calculateDistance($request->latitude, $request->longitude);
        if ($distance > self::MAX_DISTANCE_KM) {
            return response()->json(['message' => "Anda berada di luar jangkauan area sekolah. Jarak: " . round($distance, 2) . " KM.", 'status' => 'error'], 403);
        }

        // 3. Tentukan Tipe Absensi (Masuk atau Pulang)
        $latestAbsensi = Absensi::where('siswa_id', $siswa->id)
            ->where('tanggal', $today)
            ->latest('waktu_tap')
            ->first();

        // Jika belum ada absensi hari ini, berarti ABSEN MASUK
        if (empty($latestAbsensi)) {
            $type = 'masuk';
            $status = ($now->toTimeString() > self::TIME_IN_LIMIT) ? 'terlambat' : 'tepat_waktu';
        }
        // Jika sudah ada absensi masuk (atau status terakhir bukan pulang), berarti ABSEN PULANG
        else if ($latestAbsensi->type !== 'pulang') {
            $type = 'pulang';
            $status = ($now->toTimeString() < self::TIME_OUT_LIMIT) ? 'pulang_awal' : 'normal';
        }
        // Sudah absen masuk dan pulang hari ini
        else {
            return response()->json(['message' => 'Anda sudah melakukan absensi masuk dan pulang hari ini.', 'status' => 'info'], 409);
        }


        // 4. Simpan Absensi
        Absensi::create([
            'siswa_id' => $siswa->id,
            'kode_barcode' => $barcode,
            'tanggal' => $today,
            'waktu_tap' => $now, // Gunakan kolom yang lebih jelas
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'type' => $type, // 'masuk' atau 'pulang'
            'status' => $status, // 'tepat_waktu', 'terlambat', 'normal', 'pulang_awal'
        ]);

        return response()->json(['message' => "Absensi {$type} berhasil dicatat. Status: {$status}", 'status' => 'success']);
    }

    /**
     * Hitung jarak Haversine antara dua titik koordinat (dalam KM).
     */
    private function calculateDistance($lat1, $lon1)
    {
        $lat2 = self::SCHOOL_LATITUDE;
        $lon2 = self::SCHOOL_LONGITUDE;

        $earthRadius = 6371; // Radius Bumi dalam KM

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        return $distance; // Jarak dalam KM
    }
}
