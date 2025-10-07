@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="space-y-8">
        <!-- Welcome Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 overflow-hidden shadow-lg rounded-xl">
            <div class="p-8 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">Selamat Datang, {{ $user->name }}! 👋</h1>
                        <p class="text-blue-100 text-lg">{{ ucfirst($user->role) }} - Sistem Absensi</p>
                        <p class="text-blue-200 text-sm mt-1">{{ now()->isoFormat('dddd, D MMMM Y') }}</p>
                    </div>
                    <div class="hidden md:block">
                        <div class="w-24 h-24 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Siswa -->
            <div
                class="bg-white overflow-hidden shadow-lg rounded-xl border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Total Siswa</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalSiswa }}</p>
                            <div class="flex items-center mt-2">
                                <span class="text-sm text-green-600 font-medium">+12% dari bulan lalu</span>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <div
                                class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Kelas -->
            <div
                class="bg-white overflow-hidden shadow-lg rounded-xl border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Total Kelas</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalKelas }}</p>
                            <div class="flex items-center mt-2">
                                <span class="text-sm text-green-600 font-medium">Aktif semua</span>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <div
                                class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hadir Hari Ini -->
            <div
                class="bg-white overflow-hidden shadow-lg rounded-xl border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Hadir Hari Ini</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $todayPresent }}</p>
                            <div class="flex items-center mt-2">
                                <span
                                    class="text-sm text-emerald-600 font-medium">{{ $totalSiswa > 0 ? round(($todayPresent / $totalSiswa) * 100) : 0 }}%
                                    kehadiran</span>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <div
                                class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Terlambat Hari Ini -->
            <div
                class="bg-white overflow-hidden shadow-lg rounded-xl border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Terlambat Hari Ini</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $todayLate }}</p>
                            <div class="flex items-center mt-2">
                                <span class="text-sm text-yellow-600 font-medium">Perlu perhatian</span>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <div
                                class="w-16 h-16 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Recent Attendance - 2 columns -->
            <div class="lg:col-span-2">
                <div class="bg-white overflow-hidden shadow-lg rounded-xl border border-gray-100">
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-semibold text-gray-900">Absensi Terbaru</h3>
                            <a href="{{ route('absensi.index') }}"
                                class="text-blue-600 hover:text-blue-700 text-sm font-medium">Lihat Semua</a>
                        </div>
                    </div>
                    <div class="p-6">
                        @if ($recentAttendance->count() > 0)
                            <div class="space-y-4">
                                @foreach ($recentAttendance as $absensi)
                                    <div
                                        class="flex items-center space-x-4 p-4 border border-gray-100 rounded-lg hover:bg-gray-50 transition-colors">
                                        <div class="flex-shrink-0">
                                            <div
                                                class="w-12 h-12 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center">
                                                <span class="text-sm font-semibold text-gray-700">
                                                    {{ substr($absensi->siswa->nama_lengkap, 0, 2) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-semibold text-gray-900 truncate">
                                                {{ $absensi->siswa->nama_lengkap }}</p>
                                            <p class="text-sm text-gray-600">{{ $absensi->siswa->kelas->nama_kelas }} •
                                                {{ $absensi->waktu_tap->format('H:i') }}</p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                            @if ($absensi->jenis_tap === 'masuk') bg-green-100 text-green-800
                                            @else bg-blue-100 text-blue-800 @endif">
                                                @if ($absensi->jenis_tap === 'masuk')
                                                    🟢 Masuk
                                                @else
                                                    🔵 Pulang
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                        d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                    </path>
                                </svg>
                                <h3 class="mt-4 text-lg font-medium text-gray-900">Belum ada absensi</h3>
                                <p class="mt-2 text-gray-500">Belum ada data absensi untuk hari ini</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Class Summary - 1 column -->
            <div class="lg:col-span-1">
                <div class="bg-white overflow-hidden shadow-lg rounded-xl border border-gray-100">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-xl font-semibold text-gray-900">Ringkasan Kelas</h3>
                    </div>
                    <div class="p-6">
                        @if (count($classSummary) > 0)
                            <div class="space-y-4">
                                @foreach ($classSummary as $summary)
                                    <div
                                        class="border border-gray-100 rounded-lg p-4 hover:border-blue-200 transition-colors">
                                        <div class="flex items-center justify-between mb-3">
                                            <h4 class="font-semibold text-gray-900">{{ $summary['kelas']->nama_kelas }}
                                            </h4>
                                            <span
                                                class="text-lg font-bold text-blue-600">{{ $summary['persentase_kehadiran'] }}%</span>
                                        </div>
                                        <div class="grid grid-cols-2 gap-2 text-sm mb-3">
                                            <div class="text-center p-2 bg-green-50 rounded">
                                                <div class="font-semibold text-green-700">{{ $summary['hadir_hari_ini'] }}
                                                </div>
                                                <div class="text-green-600 text-xs">Hadir</div>
                                            </div>
                                            <div class="text-center p-2 bg-red-50 rounded">
                                                <div class="font-semibold text-red-700">{{ $summary['tidak_hadir'] }}
                                                </div>
                                                <div class="text-red-600 text-xs">Tidak Hadir</div>
                                            </div>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-gradient-to-r from-green-500 to-green-600 h-2 rounded-full transition-all duration-500"
                                                style="width: {{ $summary['persentase_kehadiran'] }}%"></div>
                                        </div>
                                        <div class="text-center text-xs text-gray-500 mt-2">
                                            Total: {{ $summary['total_siswa'] }} siswa
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"></path>
                                </svg>
                                <p class="text-gray-500 mt-2">Belum ada data kelas</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white overflow-hidden shadow-lg rounded-xl border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-xl font-semibold text-gray-900">Aksi Cepat</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="{{ route('siswa.create') }}"
                        class="group flex flex-col items-center justify-center p-6 border-2 border-dashed border-gray-200 rounded-xl hover:border-blue-300 hover:bg-blue-50 transition-all duration-200">
                        <div
                            class="w-12 h-12 bg-blue-100 group-hover:bg-blue-200 rounded-xl flex items-center justify-center mb-3 transition-colors">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-gray-900 group-hover:text-blue-700">Tambah Siswa</span>
                        <span class="text-xs text-gray-500 group-hover:text-blue-600 mt-1">Daftarkan siswa baru</span>
                    </a>

                    <a href="{{ route('kelas.create') }}"
                        class="group flex flex-col items-center justify-center p-6 border-2 border-dashed border-gray-200 rounded-xl hover:border-green-300 hover:bg-green-50 transition-all duration-200">
                        <div
                            class="w-12 h-12 bg-green-100 group-hover:bg-green-200 rounded-xl flex items-center justify-center mb-3 transition-colors">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-gray-900 group-hover:text-green-700">Tambah Kelas</span>
                        <span class="text-xs text-gray-500 group-hover:text-green-600 mt-1">Buat kelas baru</span>
                    </a>

                    <a href="{{ route('absensi.reports') }}"
                        class="group flex flex-col items-center justify-center p-6 border-2 border-dashed border-gray-200 rounded-xl hover:border-purple-300 hover:bg-purple-50 transition-all duration-200">
                        <div
                            class="w-12 h-12 bg-purple-100 group-hover:bg-purple-200 rounded-xl flex items-center justify-center mb-3 transition-colors">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-gray-900 group-hover:text-purple-700">Lihat Laporan</span>
                        <span class="text-xs text-gray-500 group-hover:text-purple-600 mt-1">Analisis data</span>
                    </a>

                    <a href="{{ route('absensi.create') }}"
                        class="group flex flex-col items-center justify-center p-6 border-2 border-dashed border-gray-200 rounded-xl hover:border-orange-300 hover:bg-orange-50 transition-all duration-200">
                        <div
                            class="w-12 h-12 bg-orange-100 group-hover:bg-orange-200 rounded-xl flex items-center justify-center mb-3 transition-colors">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-gray-900 group-hover:text-orange-700">Input Manual</span>
                        <span class="text-xs text-gray-500 group-hover:text-orange-600 mt-1">Koreksi absensi</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
