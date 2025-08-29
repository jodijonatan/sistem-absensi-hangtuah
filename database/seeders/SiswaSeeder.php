<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Siswa;
use App\Models\Kelas;

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
            ['nis' => '2024001', 'nama_lengkap' => 'Ahmad Rizki Pratama', 'jenis_kelamin' => 'L', 'uid_rfid' => 'A1B2C3D4'],
            ['nis' => '2024002', 'nama_lengkap' => 'Siti Nurhaliza', 'jenis_kelamin' => 'P', 'uid_rfid' => 'E5F6G7H8'],
            ['nis' => '2024003', 'nama_lengkap' => 'Budi Setiawan', 'jenis_kelamin' => 'L', 'uid_rfid' => 'I9J0K1L2'],
            
            // Class 10-B students
            ['nis' => '2024004', 'nama_lengkap' => 'Maya Sari Dewi', 'jenis_kelamin' => 'P', 'uid_rfid' => 'M3N4O5P6'],
            ['nis' => '2024005', 'nama_lengkap' => 'Eko Prasetyo', 'jenis_kelamin' => 'L', 'uid_rfid' => 'Q7R8S9T0'],
            ['nis' => '2024006', 'nama_lengkap' => 'Dewi Kartika', 'jenis_kelamin' => 'P', 'uid_rfid' => 'U1V2W3X4'],
            
            // Class 11-A IPA students
            ['nis' => '2023001', 'nama_lengkap' => 'Fahmi Abdullah', 'jenis_kelamin' => 'L', 'uid_rfid' => 'Y5Z6A7B8'],
            ['nis' => '2023002', 'nama_lengkap' => 'Rina Wulandari', 'jenis_kelamin' => 'P', 'uid_rfid' => 'C9D0E1F2'],
            ['nis' => '2023003', 'nama_lengkap' => 'Dimas Kurniawan', 'jenis_kelamin' => 'L', 'uid_rfid' => 'G3H4I5J6'],
            
            // Class 11-B IPA students
            ['nis' => '2023004', 'nama_lengkap' => 'Putri Maharani', 'jenis_kelamin' => 'P', 'uid_rfid' => 'K7L8M9N0'],
            ['nis' => '2023005', 'nama_lengkap' => 'Arif Rahman', 'jenis_kelamin' => 'L', 'uid_rfid' => 'O1P2Q3R4'],
            ['nis' => '2023006', 'nama_lengkap' => 'Indira Safitri', 'jenis_kelamin' => 'P', 'uid_rfid' => 'S5T6U7V8'],
            
            // Students without RFID cards yet (to demonstrate nullable uid_rfid)
            ['nis' => '2024007', 'nama_lengkap' => 'Rudi Hartono', 'jenis_kelamin' => 'L', 'uid_rfid' => null],
            ['nis' => '2024008', 'nama_lengkap' => 'Fitri Rahmawati', 'jenis_kelamin' => 'P', 'uid_rfid' => null]
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
                'uid_rfid' => $student['uid_rfid'],
                'jenis_kelamin' => $student['jenis_kelamin'],
                'foto' => null // Can be added later
            ]);
            
            // Move to next class every 3 students
            if (($index + 1) % 3 == 0) {
                $classIndex++;
            }
        }
    }
}
