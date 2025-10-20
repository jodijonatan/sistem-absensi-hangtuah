<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\Kelas;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    /**
     * Display a listing of attendance records
     */
    public function index(Request $request)
    {
        $query = Absensi::with(['siswa', 'siswa.kelas']);

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('waktu_tap', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('waktu_tap', '<=', $request->date_to);
        }

        // Default to today if no date specified
        if (!$request->filled('date_from') && !$request->filled('date_to')) {
            $query->whereDate('waktu_tap', Carbon::today());
        }

        // Class filter
        if ($request->filled('kelas_id')) {
            $query->whereHas('siswa', function ($q) use ($request) {
                $q->where('kelas_id', $request->kelas_id);
            });
        }

        // Student filter
        if ($request->filled('siswa_id')) {
            $query->where('siswa_id', $request->siswa_id);
        }

        // Type filter (masuk/pulang)
        if ($request->filled('jenis_tap')) {
            $query->where('jenis_tap', $request->jenis_tap);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $absensi = $query->orderBy('waktu_tap', 'desc')->paginate(20);
        $kelas = Kelas::orderBy('nama_kelas')->get();

        return view('absensi.index', compact('absensi', 'kelas'));
    }

    /**
     * Show attendance reports
     */
    public function reports(Request $request)
    {
        $dateFrom = $request->input('date_from', Carbon::now()->startOfMonth()->toDateString());
        $dateTo = $request->input('date_to', Carbon::now()->toDateString());
        $kelasId = $request->input('kelas_id');

        // Summary statistics
        $query = Absensi::whereBetween('waktu_tap', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);

        if ($kelasId) {
            $query->whereHas('siswa', function ($q) use ($kelasId) {
                $q->where('kelas_id', $kelasId);
            });
        }

        // pakai clone agar query independen
        $totalRecords = (clone $query)->count();
        $totalMasuk = (clone $query)->byType('masuk')->count();
        $totalPulang = (clone $query)->byType('pulang')->count();
        $totalTerlambat = (clone $query)->byStatus('terlambat')->count();
        $totalPulangAwal = (clone $query)->byStatus('pulang_awal')->count();


        // Daily attendance summary
        $dailySummary = DB::table('absensi')
            ->join('siswa', 'absensi.siswa_id', '=', 'siswa.id')
            ->join('kelas', 'siswa.kelas_id', '=', 'kelas.id')
            ->select(
                DB::raw('DATE(waktu_tap) as tanggal'),
                DB::raw('COUNT(CASE WHEN jenis_tap = "masuk" THEN 1 END) as total_masuk'),
                DB::raw('COUNT(CASE WHEN jenis_tap = "pulang" THEN 1 END) as total_pulang'),
                DB::raw('COUNT(CASE WHEN status = "terlambat" THEN 1 END) as total_terlambat'),
                DB::raw('COUNT(CASE WHEN status = "pulang_awal" THEN 1 END) as total_pulang_awal')
            )
            ->whereBetween('waktu_tap', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->when($kelasId, function ($query) use ($kelasId) {
                return $query->where('kelas.id', $kelasId);
            })
            ->groupBy(DB::raw('DATE(waktu_tap)'))
            ->orderBy('tanggal', 'desc')
            ->get();

        // Class-wise summary
        $classSummary = DB::table('absensi')
            ->join('siswa', 'absensi.siswa_id', '=', 'siswa.id')
            ->join('kelas', 'siswa.kelas_id', '=', 'kelas.id')
            ->select(
                'kelas.nama_kelas',
                'kelas.id as kelas_id',
                DB::raw('COUNT(CASE WHEN jenis_tap = "masuk" THEN 1 END) as total_masuk'),
                DB::raw('COUNT(CASE WHEN jenis_tap = "pulang" THEN 1 END) as total_pulang'),
                DB::raw('COUNT(CASE WHEN status = "terlambat" THEN 1 END) as total_terlambat'),
                DB::raw('COUNT(CASE WHEN status = "pulang_awal" THEN 1 END) as total_pulang_awal'),
                DB::raw('COUNT(DISTINCT siswa.id) as total_siswa_hadir')
            )
            ->whereBetween('waktu_tap', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->when($kelasId, function ($query) use ($kelasId) {
                return $query->where('kelas.id', $kelasId);
            })
            ->groupBy('kelas.id', 'kelas.nama_kelas')
            ->orderBy('kelas.nama_kelas')
            ->get();

        $kelas = Kelas::orderBy('nama_kelas')->get();

        return view('absensi.reports', compact(
            'totalRecords',
            'totalMasuk',
            'totalPulang',
            'totalTerlambat',
            'totalPulangAwal',
            'dailySummary',
            'classSummary',
            'kelas',
            'dateFrom',
            'dateTo',
            'kelasId'
        ));
    }

    /**
     * Show detailed attendance for a specific student
     */
    public function studentDetail(Request $request, Siswa $siswa)
    {
        $dateFrom = $request->input('date_from', Carbon::now()->startOfMonth()->toDateString());
        $dateTo = $request->input('date_to', Carbon::now()->toDateString());

        $absensi = $siswa->absensi()
            ->whereBetween('waktu_tap', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->orderBy('waktu_tap', 'desc')
            ->paginate(15);

        // Statistics for the period
        $stats = [
            'total_masuk' => $siswa->absensi()->whereBetween('waktu_tap', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])->byType('masuk')->count(),
            'total_pulang' => $siswa->absensi()->whereBetween('waktu_tap', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])->byType('pulang')->count(),
            'total_terlambat' => $siswa->absensi()->whereBetween('waktu_tap', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])->byStatus('terlambat')->count(),
            'total_pulang_awal' => $siswa->absensi()->whereBetween('waktu_tap', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])->byStatus('pulang_awal')->count()
        ];

        return view('absensi.student-detail', compact('siswa', 'absensi', 'stats', 'dateFrom', 'dateTo'));
    }

    /**
     * Show the form for creating a new resource (manual attendance entry)
     */
    public function create()
    {
        $siswa = Siswa::with('kelas')->orderBy('nama_lengkap')->get();
        return view('absensi.create', compact('siswa'));
    }

    /**
     * Store a newly created resource in storage (manual attendance entry)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'waktu_tap' => 'required|date',
            'jenis_tap' => 'required|in:masuk,pulang',
            'status' => 'required|in:hadir,terlambat,pulang_awal'
        ]);

        Absensi::create($validated);

        return redirect()->route('absensi.index')
            ->with('success', 'Data absensi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Absensi $absensi)
    {
        $absensi->load(['siswa', 'siswa.kelas']);
        return view('absensi.show', compact('absensi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Absensi $absensi)
    {
        $siswa = Siswa::with('kelas')->orderBy('nama_lengkap')->get();
        return view('absensi.edit', compact('absensi', 'siswa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Absensi $absensi)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'waktu_tap' => 'required|date',
            'jenis_tap' => 'required|in:masuk,pulang',
            'status' => 'required|in:hadir,terlambat,pulang_awal'
        ]);

        $absensi->update($validated);

        return redirect()->route('absensi.index')
            ->with('success', 'Data absensi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Absensi $absensi)
    {
        $absensi->delete();

        return redirect()->route('absensi.index')
            ->with('success', 'Data absensi berhasil dihapus.');
    }

    /**
     * Export attendance data
     */
    public function export(Request $request)
    {
        $dateFrom = $request->input('date_from', Carbon::now()->startOfMonth()->toDateString());
        $dateTo = $request->input('date_to', Carbon::now()->toDateString());
        $kelasId = $request->input('kelas_id');

        $query = Absensi::with(['siswa', 'siswa.kelas'])
            ->whereBetween('waktu_tap', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);

        if ($kelasId) {
            $query->whereHas('siswa', function ($q) use ($kelasId) {
                $q->where('kelas_id', $kelasId);
            });
        }

        $absensi = $query->orderBy('waktu_tap', 'desc')->get();

        return response()->json([
            'data' => $absensi->map(function ($a) {
                return [
                    'tanggal' => $a->waktu_tap->format('Y-m-d'),
                    'waktu' => $a->waktu_tap->format('H:i:s'),
                    'nama_siswa' => $a->siswa->nama_lengkap,
                    'nis' => $a->siswa->nis,
                    'kelas' => $a->siswa->kelas->nama_kelas,
                    'jenis_tap' => $a->jenis_tap,
                    'status' => $a->status
                ];
            })
        ]);
    }
}
