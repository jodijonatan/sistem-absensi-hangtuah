<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Absensi;
use App\Models\JadwalPelajaran;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class RfidApiController extends Controller
{
    /**
     * Handle RFID tap submission from reader device
     * 
     * Expected JSON payload:
     * {
     *   "uid": "A1B2C3D4",
     *   "timestamp": "2024-08-29 08:30:00" (optional, will use current time if not provided)
     * }
     */
    public function tapCard(Request $request): JsonResponse
    {
        try {
            // Validate request
            $validated = $request->validate([
                'uid' => 'required|string|max:255',
                'timestamp' => 'nullable|date_format:Y-m-d H:i:s'
            ]);
            
            $uid = $validated['uid'];
            $tapTime = isset($validated['timestamp']) ? 
                Carbon::parse($validated['timestamp']) : 
                Carbon::now();
            
            // Find student by RFID UID
            $siswa = Siswa::byRfidUid($uid)->with('kelas')->first();
            
            if (!$siswa) {
                Log::warning("RFID tap with unknown UID: {$uid}");
                return response()->json([
                    'success' => false,
                    'message' => 'Kartu RFID tidak terdaftar',
                    'code' => 'CARD_NOT_REGISTERED'
                ], 404);
            }
            
            if (!$siswa->kelas) {
                Log::error("Student {$siswa->id} has no class assigned");
                return response()->json([
                    'success' => false,
                    'message' => 'Siswa belum memiliki kelas',
                    'code' => 'NO_CLASS_ASSIGNED'
                ], 400);
            }
            
            // Get today's schedule for the student's class
            $schedule = JadwalPelajaran::getCurrentDaySchedule($siswa->kelas_id);
            
            if (!$schedule) {
                Log::warning("No schedule found for class {$siswa->kelas_id} on " . $tapTime->format('l'));
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada jadwal untuk hari ini',
                    'code' => 'NO_SCHEDULE'
                ], 400);
            }
            
            // Determine if this is check-in or check-out
            $today = $tapTime->toDateString();
            $lastAttendance = Absensi::getLastAttendanceForStudentToday($siswa->id, $today);
            
            $jenisTap = 'masuk'; // Default to check-in
            $status = 'hadir';   // Default status
            
            if ($lastAttendance) {
                // If last attendance was check-in, this should be check-out
                if ($lastAttendance->jenis_tap === 'masuk') {
                    $jenisTap = 'pulang';
                    // Check if leaving early
                    $status = $schedule->isEarlyDeparture($tapTime) ? 'pulang_awal' : 'hadir';
                } else {
                    // If last was check-out, this is another check-in (maybe after break)
                    $jenisTap = 'masuk';
                    $status = $schedule->isLateEntry($tapTime) ? 'terlambat' : 'hadir';
                }
            } else {
                // First tap of the day - check if late
                $jenisTap = 'masuk';
                $status = $schedule->isLateEntry($tapTime) ? 'terlambat' : 'hadir';
            }
            
            // Create attendance record
            $absensi = Absensi::create([
                'siswa_id' => $siswa->id,
                'waktu_tap' => $tapTime,
                'jenis_tap' => $jenisTap,
                'status' => $status
            ]);
            
            // Log successful tap
            Log::info("RFID tap recorded", [
                'siswa_id' => $siswa->id,
                'siswa_nama' => $siswa->nama_lengkap,
                'kelas' => $siswa->kelas->nama_kelas,
                'uid' => $uid,
                'jenis_tap' => $jenisTap,
                'status' => $status,
                'waktu_tap' => $tapTime->format('Y-m-d H:i:s')
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Absensi berhasil dicatat',
                'data' => [
                    'siswa' => [
                        'nama' => $siswa->nama_lengkap,
                        'nis' => $siswa->nis,
                        'kelas' => $siswa->kelas->nama_kelas
                    ],
                    'absensi' => [
                        'jenis_tap' => $jenisTap,
                        'status' => $status,
                        'waktu' => $tapTime->format('Y-m-d H:i:s'),
                        'waktu_display' => $tapTime->format('H:i:s')
                    ]
                ]
            ], 201);
            
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('RFID tap processing error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem',
                'code' => 'SYSTEM_ERROR'
            ], 500);
        }
    }
    
    /**
     * Get student info by RFID UID (for testing/verification)
     */
    public function getStudentByUid(Request $request): JsonResponse
    {
        $request->validate([
            'uid' => 'required|string|max:255'
        ]);
        
        $siswa = Siswa::byRfidUid($request->uid)->with('kelas')->first();
        
        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Kartu RFID tidak terdaftar'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'nama' => $siswa->nama_lengkap,
                'nis' => $siswa->nis,
                'kelas' => $siswa->kelas->nama_kelas ?? 'Tidak ada kelas',
                'jenis_kelamin' => $siswa->jenis_kelamin
            ]
        ]);
    }
    
    /**
     * Test endpoint to verify API is working
     */
    public function test(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'RFID API is working',
            'timestamp' => Carbon::now()->format('Y-m-d H:i:s'),
            'server_time' => Carbon::now()->toISOString()
        ]);
    }
}
