@extends('layouts.app')

@section('title', 'Edit Kelas')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center space-x-4">
        <a href="{{ route('kelas.index') }}" class="text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Kelas: {{ $kelas->nama_kelas }}</h1>
            <p class="text-gray-600">Perbarui informasi kelas</p>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <form method="POST" action="{{ route('kelas.update', $kelas) }}" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Kelas -->
                <div>
                    <label for="nama_kelas" class="block text-sm font-medium text-gray-700">Nama Kelas</label>
                    <input type="text" 
                           name="nama_kelas" 
                           id="nama_kelas" 
                           value="{{ old('nama_kelas', $kelas->nama_kelas) }}" 
                           required
                           placeholder="Contoh: 10-A, 11-B IPA, 12-A IPS"
                           class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    @error('nama_kelas')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Wali Kelas -->
                <div>
                    <label for="wali_kelas_id" class="block text-sm font-medium text-gray-700">Wali Kelas</label>
                    <select name="wali_kelas_id" 
                            id="wali_kelas_id" 
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">Pilih Wali Kelas (Opsional)</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" 
                                {{ old('wali_kelas_id', $kelas->wali_kelas_id) == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('wali_kelas_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Pilih guru yang akan menjadi wali kelas</p>
                </div>
            </div>

            <!-- Current Information -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <h3 class="text-sm font-medium text-gray-800 mb-3">Informasi Saat Ini</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600">Total Siswa:</span>
                        <span class="font-medium ml-2">{{ $kelas->siswa->count() }} siswa</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Wali Kelas Saat Ini:</span>
                        <span class="font-medium ml-2">
                            {{ $kelas->waliKelas ? $kelas->waliKelas->name : 'Belum ditentukan' }}
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-600">Dibuat:</span>
                        <span class="font-medium ml-2">{{ $kelas->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Warning if class has students -->
            @if($kelas->siswa->count() > 0)
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Perhatian</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>Kelas ini memiliki {{ $kelas->siswa->count() }} siswa. Pastikan perubahan yang Anda lakukan tidak mempengaruhi data siswa yang sudah ada.</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('kelas.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-gray-600 focus:bg-gray-600 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Update Kelas
                </button>
            </div>
        </form>
    </div>
</div>
@endsection