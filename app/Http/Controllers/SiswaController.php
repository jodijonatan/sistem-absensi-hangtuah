<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Siswa::with('kelas');

        // Filter by class if specified
        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        $siswa = $query->orderBy('nama_lengkap')->paginate(15);
        $kelas = Kelas::orderBy('nama_kelas')->get();

        return view('siswa.index', compact('siswa', 'kelas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();
        return view('siswa.create', compact('kelas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nis' => 'required|string|unique:siswa,nis|max:20',
            'nama_lengkap' => 'required|string|max:255',
            'kelas_id' => 'required|exists:kelas,id',
            'jenis_kelamin' => 'required|in:L,P',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $validated['password'] = bcrypt($validated['nis']);

        // Handle photo upload
        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('siswa-photos', 'public');
        }

        Siswa::create($validated);

        return redirect()->route('siswa.index')
            ->with('success', 'Data siswa berhasil ditambahkan. Password awal sama dengan NIS.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Siswa $siswa)
    {
        $siswa->load(['kelas', 'absensi' => function ($query) {
            $query->orderBy('waktu_tap', 'desc')->limit(10);
        }]);

        // Get attendance statistics
        $attendanceStats = [
            'total_masuk' => $siswa->absensi()->byType('masuk')->count(),
            'total_pulang' => $siswa->absensi()->byType('pulang')->count(),
            'total_terlambat' => $siswa->absensi()->byStatus('terlambat')->count(),
            'total_pulang_awal' => $siswa->absensi()->byStatus('pulang_awal')->count()
        ];

        return view('siswa.show', compact('siswa', 'attendanceStats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Siswa $siswa)
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();
        return view('siswa.edit', compact('siswa', 'kelas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'nis' => ['required', 'string', 'max:20', Rule::unique('siswa')->ignore($siswa->id)],
            'nama_lengkap' => 'required|string|max:255',
            'kelas_id' => 'required|exists:kelas,id',
            'jenis_kelamin' => 'required|in:L,P',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Handle photo upload
        if ($request->hasFile('foto')) {
            // Delete old photo if exists
            if ($siswa->foto) {
                Storage::disk('public')->delete($siswa->foto);
            }
            $validated['foto'] = $request->file('foto')->store('siswa-photos', 'public');
        }

        $siswa->update($validated);

        return redirect()->route('siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Siswa $siswa)
    {
        // Delete photo if exists
        if ($siswa->foto) {
            Storage::disk('public')->delete($siswa->foto);
        }

        $siswa->delete();

        return redirect()->route('siswa.index')
            ->with('success', 'Data siswa berhasil dihapus.');
    }

    /**
     * Export students data
     */
    public function export()
    {
        // This can be enhanced with Excel export functionality
        $siswa = Siswa::with('kelas')->get();

        return response()->json([
            'data' => $siswa->map(function ($s) {
                return [
                    'nis' => $s->nis,
                    'nama_lengkap' => $s->nama_lengkap,
                    'kelas' => $s->kelas->nama_kelas,
                    'jenis_kelamin' => $s->jenis_kelamin,
                ];
            })
        ]);
    }
}
