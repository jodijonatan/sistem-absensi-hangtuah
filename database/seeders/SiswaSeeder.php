<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = Kelas::all();

        // Sample students data
        $studentsData = [
            // Class 10-A students
            ['nis' => '2024001', 'nama_lengkap' => 'Ahmad Rizki Pratama', 'jenis_kelamin' => 'L', 'password' => Hash::make('siswa2401')],
        ];

        // Distribute students across classes
        $classIndex = 0;
        foreach ($studentsData as $index => $student) {
            // Cycle through available classes
            $kelas = $classes->get($classIndex % $classes->count());

            Siswa::create([
                'nis' => $student['nis'],
                'nama_lengkap' => $student['nama_lengkap'],
                'kelas_id' => $kelas->id,
                'jenis_kelamin' => $student['jenis_kelamin'],
                'password' => $student['password'],
                'foto' => null // Can be added later
            ]);

            // Move to next class every 3 students
            if (($index + 1) % 3 == 0) {
                $classIndex++;
            }
        }
    }
}
