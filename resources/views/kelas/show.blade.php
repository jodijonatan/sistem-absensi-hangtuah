@extends('layouts.app')

@section('title', 'Detail Kelas')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('kelas.index') }}" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $kelas->nama_kelas }}</h1>
                    <p class="text-gray-600">Detail informasi kelas dan siswa</p>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('kelas.edit', $kelas) }}"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Edit Kelas
                </a>
            </div>
        </div>

        <!-- Class Info Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Class Info -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Kelas</h3>
                    <div class="space-y-3">
                        <div>
                            <span class="text-sm text-gray-600">Nama Kelas:</span>
                            <p class="text-lg font-medium text-gray-900">{{ $kelas->nama_kelas }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-600">Wali Kelas:</span>
                            <p class="text-lg font-medium text-gray-900">
                                {{ $kelas->waliKelas ? $kelas->waliKelas->name : 'Belum ditentukan' }}
                            </p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-600">Jumlah Siswa:</span>
                            <p class="text-lg font-medium text-gray-900">{{ $kelas->siswa->count() }} orang</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-600">Jumlah Jadwal:</span>
                            <p class="text-lg font-medium text-gray-900">{{ $kelas->jadwalPelajaran->count() }} hari</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Today's Attendance -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Kehadiran Hari Ini</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Sudah Masuk</span>
                            <span
                                class="text-lg font-semibold text-green-600">{{ $attendanceToday->total_masuk ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Sudah Pulang</span>
                            <span
                                class="text-lg font-semibold text-blue-600">{{ $attendanceToday->total_pulang ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Terlambat</span>
                            <span
                                class="text-lg font-semibold text-yellow-600">{{ $attendanceToday->total_terlambat ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Belum Absen</span>
                            <span class="text-lg font-semibold text-red-600">
                                {{ $kelas->siswa->count() - ($attendanceToday->total_masuk ?? 0) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h3>
                    <div class="space-y-3">
                        <a href="{{ route('siswa.index', ['kelas_id' => $kelas->id]) }}"
                            class="block w-full text-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                            Lihat Semua Siswa
                        </a>
                        <a href="{{ route('absensi.index', ['kelas_id' => $kelas->id]) }}"
                            class="block w-full text-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                            Lihat Absensi
                        </a>
                        <a href="{{ route('siswa.create') }}?kelas_id={{ $kelas->id }}"
                            class="block w-full text-center px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition">
                            Tambah Siswa Baru
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Students List -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Daftar Siswa</h3>
                    <span class="text-sm text-gray-500">{{ $kelas->siswa->count() }} siswa</span>
                </div>

                @if ($kelas->siswa->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Foto</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        NIS</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jenis Kelamin</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        RFID</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($kelas->siswa as $siswa)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($siswa->foto)
                                                <img class="h-10 w-10 rounded-full object-cover"
                                                    src="{{ asset('storage/' . $siswa->foto) }}"
                                                    alt="{{ $siswa->nama_lengkap }}">
                                            @else
                                                <div
                                                    class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                    <svg class="h-6 w-6 text-gray-400" fill="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path
                                                            d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $siswa->nis }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $siswa->nama_lengkap }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($siswa->uid_rfid)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Terdaftar
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Belum Terdaftar
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('siswa.show', $siswa) }}"
                                                    class="text-indigo-600 hover:text-indigo-900">Detail</a>
                                                <a href="{{ route('siswa.edit', $siswa) }}"
                                                    class="text-green-600 hover:text-green-900">Edit</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if ($kelas->siswa->count() > 10)
                        <div class="mt-4">
                            <a href="{{ route('siswa.index', ['kelas_id' => $kelas->id]) }}"
                                class="text-sm text-indigo-600 hover:text-indigo-900">
                                Lihat semua siswa →
                            </a>
                        </div>
                    @endif
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada siswa</h3>
                        <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan siswa pertama ke kelas ini.</p>
                        <div class="mt-6">
                            <a href="{{ route('siswa.create') }}?kelas_id={{ $kelas->id }}"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Tambah Siswa
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Schedule Information -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Jadwal Pelajaran</h3>
                    <span class="text-sm text-gray-500">{{ $kelas->jadwalPelajaran->count() }} hari terjadwal</span>
                </div>

                @if ($kelas->jadwalPelajaran->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Hari</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jam Masuk</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jam Pulang</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($kelas->jadwalPelajaran as $jadwal)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $jadwal->hari }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $jadwal->jam_masuk }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $jadwal->jam_pulang }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $jadwal->keterangan ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3a4 4 0 118 0v4M5 21h14l1-6H4l1 6z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada jadwal</h3>
                        <p class="mt-1 text-sm text-gray-500">Jadwal pelajaran belum diatur untuk kelas ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
