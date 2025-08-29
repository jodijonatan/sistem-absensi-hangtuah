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
        Schema::create('jadwal_pelajaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade'); // Foreign key ke tabel kelas
            $table->enum('hari', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']); // Hari dalam seminggu
            $table->time('jam_masuk'); // Waktu mulai jam pelajaran/sekolah
            $table->time('jam_pulang'); // Waktu selesai jam pelajaran/sekolah
            $table->string('keterangan')->nullable(); // Contoh: "Jadwal Masuk Pagi", "Jadwal Siang"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_pelajaran');
    }
};
