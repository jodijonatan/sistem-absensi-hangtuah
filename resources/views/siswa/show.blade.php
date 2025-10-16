@extends('layouts.app')

@section('title', 'Detail Siswa')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('siswa.index') }}" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Detail Siswa</h1>
                    <p class="text-gray-600">Informasi lengkap data siswa</p>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('siswa.edit', $siswa) }}"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Edit Data
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Student Information Card -->
            <div class="lg:col-span-2">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-start space-x-6">
                            <!-- Photo -->
                            <div class="flex-shrink-0">
                                @if ($siswa->foto)
                                    <img class="h-32 w-32 rounded-lg object-cover"
                                        src="{{ asset('storage/' . $siswa->foto) }}" alt="{{ $siswa->nama_lengkap }}">
                                @else
                                    <div class="h-32 w-32 rounded-lg bg-gray-300 flex items-center justify-center">
                                        <svg class="h-16 w-16 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Basic Info -->
                            <div class="flex-1 min-w-0">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $siswa->nama_lengkap }}</h3>
                                        <p class="text-sm text-gray-500">NIS: {{ $siswa->nis }}</p>
                                        <p class="text-sm text-gray-500">Kelas: {{ $siswa->kelas->nama_kelas }}</p>
                                        <p class="text-sm text-gray-500">
                                            Jenis Kelamin: {{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                        </p>
                                    </div>

                                    <!-- RFID Status -->
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-700 mb-2">Status RFID</h4>
                                        @if ($siswa->uid_rfid)
                                            <div class="flex items-center space-x-2">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Terdaftar
                                                </span>
                                                <span class="text-sm text-gray-600">{{ $siswa->uid_rfid }}</span>
                                            </div>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Belum Terdaftar
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="lg:col-span-1">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistik Kehadiran</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Total Masuk</span>
                                <span
                                    class="text-lg font-semibold text-green-600">{{ $attendanceStats['total_masuk'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Total Pulang</span>
                                <span
                                    class="text-lg font-semibold text-blue-600">{{ $attendanceStats['total_pulang'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Terlambat</span>
                                <span
                                    class="text-lg font-semibold text-yellow-600">{{ $attendanceStats['total_terlambat'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Pulang Awal</span>
                                <span
                                    class="text-lg font-semibold text-orange-600">{{ $attendanceStats['total_pulang_awal'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Attendance Records -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Riwayat Kehadiran Terbaru</h3>

                @if ($siswa->absensi->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Waktu</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jenis</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($siswa->absensi as $absen)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $absen->waktu_tap->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $absen->waktu_tap->format('H:i:s') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $absen->jenis_tap === 'masuk' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                                {{ ucfirst($absen->jenis_tap) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @switch($absen->status)
                                                @case('tepat_waktu')
                                                    bg-green-100 text-green-800
                                                    @break
                                                @case('terlambat')
                                                    bg-yellow-100 text-yellow-800
                                                    @break
                                                @case('pulang_awal')
                                                    bg-orange-100 text-orange-800
                                                    @break
                                                @default
                                                    bg-gray-100 text-gray-800
                                            @endswitch">
                                                {{ str_replace('_', ' ', ucfirst($absen->status)) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('absensi.index', ['siswa_id' => $siswa->id]) }}"
                            class="text-sm text-indigo-600 hover:text-indigo-900">
                            Lihat semua riwayat kehadiran →
                        </a>
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada data kehadiran</h3>
                        <p class="mt-1 text-sm text-gray-500">Siswa belum melakukan absensi.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
