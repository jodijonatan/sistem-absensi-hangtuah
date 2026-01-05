<?php

// app/Http/Middleware/RedirectIfAuthenticated.php

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
  public function handle(Request $request, Closure $next, string ...$guards): Response
  {
    // Tetapkan guard yang akan diperiksa: 'web' (diwakili null) dan 'siswa'
    $guards = empty($guards) ? [null, 'siswa'] : $guards;

    foreach ($guards as $guard) {
      if (Auth::guard($guard)->check()) {

        // Jika yang terautentikasi adalah guard 'siswa', arahkan ke rute siswa
        if ($guard === 'siswa') {
          return redirect()->route('siswa.dashboard');
        }

        // Jika yang terautentikasi adalah guard 'web' (Admin/Guru), arahkan ke dashboard admin
        return redirect(RouteServiceProvider::HOME);
      }
    }

    return $next($request);
  }
}
