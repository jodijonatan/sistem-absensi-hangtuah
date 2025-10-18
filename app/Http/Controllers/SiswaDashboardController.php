<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaDashboardController extends Controller
{
    public function index()
    {
        dd(Auth::guard('siswa')->check(), Auth::guard('siswa')->user());
        return view('siswa.dashboard');
    }
}
