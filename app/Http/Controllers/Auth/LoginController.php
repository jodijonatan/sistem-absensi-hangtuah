<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        // Rate limiting
        $key = Str::lower($request->input('email')).'|'.$request->ip();
        
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            
            throw ValidationException::withMessages([
                'email' => ['Terlalu banyak percobaan login. Silakan coba lagi dalam '.$seconds.' detik.']
            ]);
        }
        
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');
        
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            // Clear rate limiter on successful login
            RateLimiter::clear($key);
            
            $user = Auth::user();
            
            // Log successful login
            logger('User logged in', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $user->role,
                'ip' => $request->ip()
            ]);
            
            // Redirect based on intended URL or dashboard
            return redirect()->intended(route('dashboard'));
        }
        
        // Increment rate limiter on failed attempt
        RateLimiter::hit($key);
        
        throw ValidationException::withMessages([
            'email' => ['Email atau password yang Anda masukkan salah.']
        ]);
    }
}
