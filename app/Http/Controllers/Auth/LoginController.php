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

        $isEmail = filter_var($login, FILTER_VALIDATE_EMAIL);

        // =========================================================
        // ✅ 1️⃣ Coba login sebagai Admin/User (HANYA MENGGUNAKAN EMAIL)
        // Kita hanya menggunakan Auth::attempt() jika formatnya email.
        // =========================================================
        if ($isEmail) {
            $credentials = [
                'email' => $login,
                'password' => $password,
            ];

            // Auth::attempt() menggunakan default guard (web/users)
            if (Auth::attempt($credentials, $remember)) {
                $request->session()->regenerate();
                RateLimiter::clear($key);

                // Redirect Admin/Guru (asumsi User model memiliki kolom role)
                $user = Auth::user();
                if (in_array($user->role ?? null, ['admin', 'guru'])) {
                    return redirect()->intended(route('dashboard'));
                }
                // Jika ada kasus lain di user guard (misal, role 'lain'), redirect ke dashboard default
                return redirect()->intended(route('dashboard'));
            }
        }

        // =========================================================
        // ✅ 2️⃣ Coba login sebagai Siswa (HANYA MENGGUNAKAN NIS)
        // Kita menggunakan Hash::check() manual dan Auth::guard('siswa')->login()
        // =========================================================

        // Jika bukan email, kita asumsikan itu NIS
        if (!$isEmail) {
            $siswa = Siswa::where('nis', $login)->first();

            // Lakukan pengecekan password secara manual menggunakan Hash
            if ($siswa && Hash::check($password, $siswa->password)) {

                // Gunakan GUARD SISWA yang telah dikonfigurasi
                Auth::guard('siswa')->login($siswa, $remember);

                $request->session()->regenerate();
                RateLimiter::clear($key);

                session()->flash('login_success', 'Login Siswa BERHASIL! Sekarang cek tahap 2.');

                // Redirect Siswa ke dashboard mereka
                return redirect()->intended(route('siswa.dashboard'));
            }
        }

        // =========================================================
        // ❌ Gagal Login
        // =========================================================
        RateLimiter::hit($key);

        throw ValidationException::withMessages([
            'login' => ['NIS/Email atau password salah.']
        ]);
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        // Logout dari semua guard
        Auth::logout();
        Auth::guard('siswa')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
