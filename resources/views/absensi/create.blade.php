@extends('layouts.app')

@section('title', 'Tambah Absensi Manual')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center space-x-4">
            <a href="{{ route('absensi.index') }}" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Tambah Absensi Manual</h1>
                <p class="text-gray-600">Input data kehadiran siswa secara manual</p>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <form method="POST" action="{{ route('absensi.store') }}" class="p-6 space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Siswa -->
                    <div class="md:col-span-2">
                        <label for="siswa_id" class="block text-sm font-medium text-gray-700">Siswa</label>
                        <select name="siswa_id" id="siswa_id" required
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="">Pilih Siswa</option>
                            @foreach ($siswa as $student)
                                <option value="{{ $student->id }}" {{ old('siswa_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->nama_lengkap }} - {{ $student->nis }} ({{ $student->kelas->nama_kelas }})
                                </option>
                            @endforeach
                        </select>
                        @error('siswa_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Cari dan pilih siswa yang akan diabsen</p>
                    </div>

                    <!-- Waktu Tap -->
                    <div>
                        <label for="waktu_tap" class="block text-sm font-medium text-gray-700">Waktu Absensi</label>
                        <input type="datetime-local" name="waktu_tap" id="waktu_tap"
                            value="{{ old('waktu_tap', now()->format('Y-m-d\TH:i')) }}" required
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        @error('waktu_tap')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jenis Tap -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Absensi</label>
                        <div class="flex space-x-4">
                            <label class="flex items-center">
                                <input type="radio" name="jenis_tap" value="masuk"
                                    {{ old('jenis_tap') === 'masuk' ? 'checked' : '' }}
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                <span class="ml-2 text-sm text-gray-700">Masuk</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="jenis_tap" value="pulang"
                                    {{ old('jenis_tap') === 'pulang' ? 'checked' : '' }}
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                <span class="ml-2 text-sm text-gray-700">Pulang</span>
                            </label>
                        </div>
                        @error('jenis_tap')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status Kehadiran</label>
                        <select name="status" id="status" required
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="">Pilih Status</option>
                            <option value="hadir" {{ old('status') === 'hadir' ? 'selected' : '' }}>Hadir</option>
                            <option value="terlambat" {{ old('status') === 'terlambat' ? 'selected' : '' }}>Terlambat
                            </option>
                            <option value="pulang_awal" {{ old('status') === 'pulang_awal' ? 'selected' : '' }}>Pulang Awal
                            </option>
                        </select>
                        @error('status')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Information Card -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Informasi Penting</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Pastikan waktu dan tanggal sesuai dengan kondisi sebenarnya</li>
                                    <li>Status "Terlambat" untuk masuk setelah jam pelajaran dimulai</li>
                                    <li>Status "Pulang Awal" untuk pulang sebelum jam pelajaran berakhir</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('absensi.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-gray-600 focus:bg-gray-600 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Batal
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Simpan Absensi
                        </button>
                    </div>
            </form>
        </div>

        <!-- Recent Manual Entries -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Tips Penggunaan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                    <div>
                        <h4 class="font-medium text-gray-900 mb-2">Kapan menggunakan input manual?</h4>
                        <ul class="space-y-1">
                            <li>• Koreksi data absensi yang salah</li>
                            <li>• Input data absensi terlambat</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900 mb-2">Cara menentukan status:</h4>
                        <ul class="space-y-1">
                            <li>• <strong>Hadir:</strong> Datang/pulang tepat waktu</li>
                            <li>• <strong>Terlambat:</strong> Datang setelah jam masuk</li>
                            <li>• <strong>Pulang Awal:</strong> Pulang sebelum jam pulang</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-suggest status based on jenis_tap and current time
        document.addEventListener('DOMContentLoaded', function() {
            const jenisInputs = document.querySelectorAll('input[name="jenis_tap"]');
            const statusSelect = document.getElementById('status');
            const waktuInput = document.getElementById('waktu_tap');

            jenisInputs.forEach(input => {
                input.addEventListener('change', function() {
                    if (this.value === 'masuk') {
                        statusSelect.value = 'hadir'; // Default to hadir for masuk
                    } else if (this.value === 'pulang') {
                        statusSelect.value = 'hadir'; // Default to hadir for pulang
                    }
                });
            });
        });
    </script>
@endsection
