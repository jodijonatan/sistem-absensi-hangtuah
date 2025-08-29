<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run seeders in the correct order due to foreign key constraints
        $this->call([
            UserSeeder::class,          // First: Users (no dependencies)
            KelasSeeder::class,         // Second: Kelas (depends on Users for wali_kelas_id)
            SiswaSeeder::class,         // Third: Siswa (depends on Kelas)
            JadwalPelajaranSeeder::class // Fourth: JadwalPelajaran (depends on Kelas)
        ]);
    }
}
