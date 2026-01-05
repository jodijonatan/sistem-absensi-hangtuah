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

            // Kolom baru untuk menampung ID barcode/QR (opsional)
            $table->string('kode_barcode')->nullable();

            $table->dateTime('waktu_tap');

            // ✅ PERBAIKAN: Ganti 'jenis_tap' menjadi 'type'
            $table->enum('type', ['masuk', 'pulang']);

            $table->enum('status', ['hadir', 'terlambat', 'pulang_awal']);

            // Kolom baru untuk Geofencing (opsional)
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

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
