<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login attempt
     */
    public function login(Request $request)
    {
        // Validasi: boleh pakai email ATAU NIS
        $request->validate([
            'login' => 'required', // bisa berisi email atau nis
            'password' => 'required',
        ]);

        $key = Str::lower($request->input('login')) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            throw ValidationException::withMessages([
                'login' => ['Terlalu banyak percobaan login. Silakan coba lagi dalam ' . $seconds . ' detik.']
            ]);
        }

        $remember = $request->boolean('remember');
        $login = $request->input('login');
        $password = $request->input('password');

        // ✅ 1️⃣ Coba login sebagai user (admin/guru)
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            // Jika formatnya email
            if (Auth::attempt(['email' => $login, 'password' => $password], $remember)) {
                $request->session()->regenerate();
                RateLimiter::clear($key);

                $user = Auth::user();

                // Redirect berdasarkan role
                if ($user->role === 'admin' || $user->role === 'guru') {
                    return redirect()->route('dashboard');
                }

                if ($user->role === 'siswa') {
                    return redirect()->route('siswa.dashboard');
                }

                return redirect()->route('dashboard');
            }
        }

        // ✅ 2️⃣ Kalau bukan email, anggap itu NIS → login sebagai siswa
        $siswa = Siswa::where('nis', $login)->first();

        if ($siswa && Hash::check($password, $siswa->password)) {
            Auth::guard('siswa')->login($siswa);
            $request->session()->regenerate();
            RateLimiter::clear($key);

            return redirect()->route('siswa.dashboard');
        }

        // Gagal login
        RateLimiter::hit($key);

        throw ValidationException::withMessages([
            'login' => ['Email/NIS atau password salah.']
        ]);
    }
}
