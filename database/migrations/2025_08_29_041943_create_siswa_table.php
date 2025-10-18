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
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->string('nis')->unique(); // Nomor Induk Siswa
            $table->string('nama_lengkap'); // Nama lengkap siswa
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade'); // Foreign key ke tabel kelas
            $table->string('password');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('uid_rfid')->unique()->nullable(); // UID kartu RFID yang dipegang siswa
            $table->enum('jenis_kelamin', ['L', 'P']); // L = Laki-laki, P = Perempuan
            $table->string('foto')->nullable(); // Path ke file foto siswa
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};
