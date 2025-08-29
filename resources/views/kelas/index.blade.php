@extends('layouts.app')

@section('title', 'Manajemen Kelas')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen Kelas</h1>
            <p class="text-gray-600">Kelola data kelas dan wali kelas</p>
        </div>
        @if(Auth::user()->isAdmin())
        <a href="{{ route('kelas.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tambah Kelas
        </a>
        @endif
    </div>

    <!-- Classes Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($kelas as $kelasItem)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $kelasItem->nama_kelas }}</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $kelasItem->siswa_count }} siswa
                        </span>
                    </div>

                    @if($kelasItem->waliKelas)
                        <div class="mb-4">
                            <p class="text-sm text-gray-600">Wali Kelas:</p>
                            <p class="font-medium text-gray-900">{{ $kelasItem->waliKelas->name }}</p>
                        </div>
                    @else
                        <div class="mb-4">
                            <p class="text-sm text-gray-500 italic">Belum ada wali kelas</p>
                        </div>
                    @endif

                    <div class="flex justify-end space-x-2">
                        <a href="{{ route('kelas.show', $kelasItem) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">
                            Lihat Detail
                        </a>
                        @if(Auth::user()->isAdmin())
                        <a href="{{ route('kelas.edit', $kelasItem) }}" class="text-yellow-600 hover:text-yellow-900 text-sm">
                            Edit
                        </a>
                        <form method="POST" action="{{ route('kelas.destroy', $kelasItem) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 text-sm" onclick="return confirm('Yakin ingin menghapus kelas ini?')">
                                Hapus
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada kelas</h3>
                        <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan kelas baru.</p>
                        @if(Auth::user()->isAdmin())
                        <div class="mt-6">
                            <a href="{{ route('kelas.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Tambah Kelas
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection