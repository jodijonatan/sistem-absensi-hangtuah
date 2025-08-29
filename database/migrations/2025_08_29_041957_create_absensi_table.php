<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade'); // Foreign key ke tabel siswa
            $table->dateTime('waktu_tap'); // Waktu presisi saat siswa melakukan tap
            $table->enum('jenis_tap', ['masuk', 'pulang']); // Ditentukan oleh logika aplikasi saat tap
            $table->enum('status', ['hadir', 'terlambat', 'pulang_awal']); // Status berdasarkan perbandingan dengan jadwal
            $table->timestamps();
            
            // Index untuk optimasi query
            $table->index(['siswa_id', 'waktu_tap']);
            $table->index('waktu_tap');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
