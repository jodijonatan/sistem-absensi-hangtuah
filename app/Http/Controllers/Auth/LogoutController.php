<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    /**
     * Handle logout request for all guards
     */
    public function logout(Request $request)
    {
        // Deteksi guard mana yang sedang aktif
        $guard = Auth::check() ? 'web' : (Auth::guard('siswa')->check() ? 'siswa' : null);

        // Ambil data user untuk log
        $user = $guard ? Auth::guard($guard)->user() : null;

        // Log aktivitas logout (jika ada user yang aktif)
        if ($user) {
            logger('User logged out', [
                'user_id' => $user->id,
                'role' => $user->role ?? 'siswa',
                'guard' => $guard,
                'ip' => $request->ip(),
            ]);
        }

        // Logout sesuai guard yang aktif
        if ($guard) {
            Auth::guard($guard)->logout();
        }

        // Bersihkan sesi
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Arahkan ke login
        return redirect()->route('login')->with('message', 'Anda telah berhasil logout.');
    }
}
