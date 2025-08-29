<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\User;
use App\Models\Siswa;
use App\Models\JadwalPelajaran;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $query = Kelas::with(['waliKelas', 'siswa'])->withCount('siswa');
        
        // If user is guru, show only classes they are wali kelas for
        if ($user->isGuru()) {
            $query->where('wali_kelas_id', $user->id);
        }
        
        $kelas = $query->orderBy('nama_kelas')->get();
        
        return view('kelas.index', compact('kelas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $teachers = User::where('role', 'guru')->orderBy('name')->get();
        return view('kelas.create', compact('teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kelas' => 'required|string|unique:kelas,nama_kelas|max:255',
            'wali_kelas_id' => 'nullable|exists:users,id'
        ]);
        
        // Ensure wali kelas is a teacher
        if ($validated['wali_kelas_id']) {
            $teacher = User::find($validated['wali_kelas_id']);
            if (!$teacher || !$teacher->isGuru()) {
                return back()->withErrors(['wali_kelas_id' => 'Wali kelas harus seorang guru.']);
            }
        }
        
        Kelas::create($validated);
        
        return redirect()->route('kelas.index')
            ->with('success', 'Data kelas berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kelas $kelas)
    {
        $kelas->load(['waliKelas', 'siswa' => function($query) {
            $query->orderBy('nama_lengkap');
        }, 'jadwalPelajaran' => function($query) {
            $query->orderBy('hari');
        }]);
        
        // Get attendance summary for today
        $today = now()->toDateString();
        $attendanceToday = DB::table('absensi')
            ->join('siswa', 'absensi.siswa_id', '=', 'siswa.id')
            ->where('siswa.kelas_id', $kelas->id)
            ->whereDate('absensi.waktu_tap', $today)
            ->select(
                DB::raw('COUNT(CASE WHEN jenis_tap = "masuk" THEN 1 END) as total_masuk'),
                DB::raw('COUNT(CASE WHEN jenis_tap = "pulang" THEN 1 END) as total_pulang'),
                DB::raw('COUNT(CASE WHEN status = "terlambat" THEN 1 END) as total_terlambat')
            )
            ->first();
        
        return view('kelas.show', compact('kelas', 'attendanceToday'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kelas $kelas)
    {
        $teachers = User::where('role', 'guru')->orderBy('name')->get();
        return view('kelas.edit', compact('kelas', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kelas $kelas)
    {
        $validated = $request->validate([
            'nama_kelas' => ['required', 'string', 'max:255', Rule::unique('kelas')->ignore($kelas->id)],
            'wali_kelas_id' => 'nullable|exists:users,id'
        ]);
        
        // Ensure wali kelas is a teacher
        if ($validated['wali_kelas_id']) {
            $teacher = User::find($validated['wali_kelas_id']);
            if (!$teacher || !$teacher->isGuru()) {
                return back()->withErrors(['wali_kelas_id' => 'Wali kelas harus seorang guru.']);
            }
        }
        
        $kelas->update($validated);
        
        return redirect()->route('kelas.index')
            ->with('success', 'Data kelas berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kelas $kelas)
    {
        // Check if class has students
        if ($kelas->siswa()->count() > 0) {
            return redirect()->route('kelas.index')
                ->with('error', 'Tidak dapat menghapus kelas yang masih memiliki siswa.');
        }
        
        $kelas->delete();
        
        return redirect()->route('kelas.index')
            ->with('success', 'Data kelas berhasil dihapus.');
    }
    
    /**
     * Show schedule management for a class
     */
    public function schedules(Kelas $kelas)
    {
        $schedules = $kelas->jadwalPelajaran()->orderBy('hari')->get();
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        
        return view('kelas.schedules', compact('kelas', 'schedules', 'days'));
    }
    
    /**
     * Store schedule for a class
     */
    public function storeSchedule(Request $request, Kelas $kelas)
    {
        $validated = $request->validate([
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_masuk' => 'required|date_format:H:i',
            'jam_pulang' => 'required|date_format:H:i|after:jam_masuk',
            'keterangan' => 'nullable|string|max:255'
        ]);
        
        // Check if schedule for this day already exists
        $existing = $kelas->jadwalPelajaran()->where('hari', $validated['hari'])->first();
        
        if ($existing) {
            return back()->withErrors(['hari' => 'Jadwal untuk hari ini sudah ada.']);
        }
        
        $kelas->jadwalPelajaran()->create([
            'hari' => $validated['hari'],
            'jam_masuk' => $validated['jam_masuk'] . ':00',
            'jam_pulang' => $validated['jam_pulang'] . ':00',
            'keterangan' => $validated['keterangan']
        ]);
        
        return redirect()->route('kelas.schedules', $kelas)
            ->with('success', 'Jadwal berhasil ditambahkan.');
    }
    
    /**
     * Update schedule for a class
     */
    public function updateSchedule(Request $request, Kelas $kelas, JadwalPelajaran $schedule)
    {
        $validated = $request->validate([
            'jam_masuk' => 'required|date_format:H:i',
            'jam_pulang' => 'required|date_format:H:i|after:jam_masuk',
            'keterangan' => 'nullable|string|max:255'
        ]);
        
        $schedule->update([
            'jam_masuk' => $validated['jam_masuk'] . ':00',
            'jam_pulang' => $validated['jam_pulang'] . ':00',
            'keterangan' => $validated['keterangan']
        ]);
        
        return redirect()->route('kelas.schedules', $kelas)
            ->with('success', 'Jadwal berhasil diperbarui.');
    }
    
    /**
     * Delete schedule for a class
     */
    public function destroySchedule(Kelas $kelas, JadwalPelajaran $schedule)
    {
        $schedule->delete();
        
        return redirect()->route('kelas.schedules', $kelas)
            ->with('success', 'Jadwal berhasil dihapus.');
    }
    
    /**
     * Show students in a class
     */
    public function students(Kelas $kelas)
    {
        $students = $kelas->siswa()->orderBy('nama_lengkap')->paginate(15);
        
        return view('kelas.students', compact('kelas', 'students'));
    }
    
    /**
     * Get classes data for AJAX (used in forms)
     */
    public function getData()
    {
        $user = Auth::user();
        $query = Kelas::select('id', 'nama_kelas');
        
        if ($user->isGuru()) {
            $query->where('wali_kelas_id', $user->id);
        }
        
        $kelas = $query->orderBy('nama_kelas')->get();
        
        return response()->json($kelas);
    }
}
