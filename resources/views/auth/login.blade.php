<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login - {{ config('app.name', 'Sistem Absensi Hang Tuah') }}</title>

    <link rel="shortcut icon" href="absensi.ico" type="image/x-icon">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
            overflow: hidden;
            /* Mencegah scroll pada body */
        }

        /* 1. Kontainer Utama: Split Kiri-Kanan */
        .login-split-container {
            display: flex;
            min-height: 100vh;
        }

        /* 2. Sisi Kiri (Gambar Sekolah) */
        .left-panel {
            flex: 0 0 67%;
            /* Ambil 50% dari lebar */
            position: relative;
            background: linear-gradient(135deg, #3674B5 0%, #578FCA 100%);
            /* Warna fallback */
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        /* Ganti URL gambar dengan gambar sekolah Anda */
        .left-panel-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('{{ asset('images/background.jpg') }}');
            /* Ganti path gambar Anda */
            background-size: cover;
            background-position: center;
            filter: grayscale(10%) contrast(90%) blur(11px);
            /* Efek visual agar teks lebih menonjol */
            transform: scale(1.05);
            /* Sedikit zoom untuk fill */
        }

        /* Overlay untuk Kontras */
        .left-panel::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(43, 87, 151, 0.75);
            /* Biru gelap semi-transparan */
        }

        /* 3. Sisi Kanan (Form Login) */
        .right-panel {
            flex: 0 0 33%;
            /* Ambil 50% dari lebar */
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #ffffff;
            /* Latar belakang putih */
            padding: 2rem;
            overflow-y: auto;
            /* Mengizinkan scroll jika konten terlalu panjang */
        }

        /* Penyesuaian Style Input */
        .input-group {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
        }

        .input-with-icon {
            padding-left: 40px;
        }

        /* Responsif: Stack kolom di layar kecil */
        @media (max-width: 1024px) {
            .login-split-container {
                flex-direction: column;
            }

            .left-panel {
                height: 30vh;
                /* Panel kiri lebih pendek di HP */
                min-height: 200px;
                display: none;
                /* Opsional: Sembunyikan panel kiri di HP untuk fokus ke form */
            }

            .right-panel {
                flex: none;
                height: auto;
                min-height: 100vh;
                /* Gunakan seluruh sisa layar */
                padding: 1.5rem;
            }
        }
    </style>
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="login-split-container">

        <div class="left-panel">
            <div class="left-panel-bg" role="img" aria-label="Gedung Sekolah"></div>
            <div class="relative z-20 p-8 text-white">
                <div
                    class="w-24 h-24 bg-white bg-opacity-20 backdrop-filter backdrop-blur-sm rounded-full flex items-center justify-center mx-auto mb-6 border-4 border-white border-opacity-30 shadow-2xl">
                    <img src="{{ asset('absensi.ico') }}" alt="Logo Sekolah" class="w-16 h-16 rounded-full">
                </div>
                <h1 class="text-4xl font-extrabold mb-3 leading-tight tracking-wide">
                    Sistem Absensi <br>
                    <span class="block text-sky-200">SMA Swasta Hang Tuah Cabang Belawan</span>
                </h1>
                <p class="text-lg font-light max-w-sm mx-auto opacity-90">
                    Solusi terintegrasi untuk pengelolaan kehadiran yang efisien.
                </p>
            </div>
        </div>
        <div class="right-panel">
            <div class="w-full max-w-md">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">Masuk ke Sistem</h2>
                    <p class="text-gray-500">Silakan masukkan detail akun Anda untuk melanjutkan.</p>
                </div>

                @if (session('message'))
                    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-400 rounded-r-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">{{ session('message') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-semibold text-gray-700">Alamat Email</label>
                        <div class="input-group transition-shadow duration-300 rounded-xl hover:shadow-md">
                            <div class="input-icon">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207">
                                    </path>
                                </svg>
                            </div>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                autofocus autocomplete="username" placeholder="Masukkan email"
                                class="input-with-icon block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all duration-200 bg-white" />
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-semibold text-gray-700">Kata Sandi</label>
                        <div class="input-group transition-shadow duration-300 rounded-xl hover:shadow-md">
                            <div class="input-icon">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                            </div>
                            <input id="password" type="password" name="password" required
                                autocomplete="current-password" placeholder="Masukkan kata sandi"
                                class="input-with-icon block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all duration-200 bg-white" />
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="flex items-center">
                            <input id="remember_me" type="checkbox" name="remember"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2 transition-colors cursor-pointer">
                            <span class="ml-3 text-sm text-gray-600 font-medium">Ingat saya</span>
                        </label>
                    </div>

                    <div class="space-y-4">
                        <button type="submit"
                            class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-lg font-semibold rounded-xl text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 cursor-pointer">
                            <span class="absolute left-0 inset-y-0 flex items-center pl-4">
                                <svg class="h-6 w-6 text-blue-300 group-hover:text-blue-200 transition-colors"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                                    </path>
                                </svg>
                            </span>
                            Masuk ke Sistem
                        </button>
                    </div>
                </form>

                {{-- <div class="mt-8 p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <div class="flex items-center mb-3">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-sm font-semibold text-gray-700">Akun Demo untuk Testing:</h3>
                    </div>
                    <div class="space-y-2 text-sm">
                        <div
                            class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-3 bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition">
                            <div class="mb-2 sm:mb-0">
                                <span class="font-medium text-blue-700 block">Administrator:</span>
                                <div class="text-gray-600">admin@gmail.com / admin2401</div>
                            </div>
                            <button onclick="fillLogin('admin@gmail.com', 'admin2401')" type="button"
                                class="text-blue-600 hover:text-blue-800 text-xs font-medium px-3 py-1 bg-blue-50 rounded-lg hover:bg-blue-100 transition cursor-pointer">
                                Gunakan
                            </button>
                        </div>
                        <div
                            class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-3 bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition">
                            <div class="mb-2 sm:mb-0">
                                <span class="font-medium text-green-700 block">Guru:</span>
                                <div class="text-gray-600">budi@gmail.com / guru2401</div>
                            </div>
                            <button onclick="fillLogin('budi@gmail.com', 'guru2401')" type="button"
                                class="text-green-600 hover:text-green-800 text-xs font-medium px-3 py-1 bg-green-50 rounded-lg hover:bg-green-100 transition cursor-pointer">
                                Gunakan
                            </button>
                        </div>
                    </div>
                </div> --}}

                <div class="text-center mt-8">
                    <p class="text-gray-400 text-xs">
                        © {{ date('Y') }} Sistem Absensi Hangtuah Belawan. Dikembangkan oleh Jodi Jonatan
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function fillLogin(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }

        // Script tambahan untuk interaksi minimal (dihapus karena tidak relevan di tata letak baru)
    </script>
</body>

</html>
