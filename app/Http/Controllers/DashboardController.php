<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Absensi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with statistics and overview
     */
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();
        
        // Basic statistics
        $totalSiswa = Siswa::count();
        $totalKelas = Kelas::count();
        $totalGuru = User::where('role', 'guru')->count();
        
        // Today's attendance statistics
        $todayAttendance = Absensi::forDate($today)->count();
        $todayPresent = Absensi::forDate($today)->byType('masuk')->count();
        $todayLate = Absensi::forDate($today)->byStatus('terlambat')->count();
        
        // Recent attendance records (last 10)
        $recentAttendance = Absensi::with(['siswa', 'siswa.kelas'])
            ->orderBy('waktu_tap', 'desc')
            ->limit(10)
            ->get();
        
        // Classes overview for teachers
        $kelasData = [];
        if ($user->isGuru()) {
            // Show only classes where user is wali kelas
            $kelasData = Kelas::where('wali_kelas_id', $user->id)
                ->withCount('siswa')
                ->get();
        } else {
            // Show all classes for admin
            $kelasData = Kelas::withCount('siswa')
                ->with('waliKelas')
                ->get();
        }
        
        // Today's attendance summary by class
        $classSummary = [];
        foreach ($kelasData as $kelas) {
            $totalStudentsInClass = $kelas->siswa_count;
            $presentToday = Absensi::forDate($today)
                ->byType('masuk')
                ->whereHas('siswa', function ($query) use ($kelas) {
                    $query->where('kelas_id', $kelas->id);
                })
                ->count();
            
            $classSummary[] = [
                'kelas' => $kelas,
                'total_siswa' => $totalStudentsInClass,
                'hadir_hari_ini' => $presentToday,
                'tidak_hadir' => $totalStudentsInClass - $presentToday,
                'persentase_kehadiran' => $totalStudentsInClass > 0 ? 
                    round(($presentToday / $totalStudentsInClass) * 100, 1) : 0
            ];
        }
        
        return view('dashboard', compact(
            'totalSiswa',
            'totalKelas', 
            'totalGuru',
            'todayAttendance',
            'todayPresent',
            'todayLate',
            'recentAttendance',
            'classSummary',
            'user'
        ));
    }
    
    /**
     * Get dashboard data for AJAX requests
     */
    public function getData(Request $request)
    {
        $today = Carbon::today();
        
        return response()->json([
            'total_siswa' => Siswa::count(),
            'total_kelas' => Kelas::count(),
            'today_present' => Absensi::forDate($today)->byType('masuk')->count(),
            'today_late' => Absensi::forDate($today)->byStatus('terlambat')->count(),
            'recent_attendance' => Absensi::with(['siswa', 'siswa.kelas'])
                ->forDate($today)
                ->orderBy('waktu_tap', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($absensi) {
                    return [
                        'siswa_nama' => $absensi->siswa->nama_lengkap,
                        'kelas' => $absensi->siswa->kelas->nama_kelas,
                        'waktu' => $absensi->waktu_tap->format('H:i:s'),
                        'jenis' => $absensi->jenis_tap,
                        'status' => $absensi->status
                    ];
                })
        ]);
    }
}
