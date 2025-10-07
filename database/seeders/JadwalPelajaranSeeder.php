<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JadwalPelajaran;
use App\Models\Kelas;

class JadwalPelajaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = Kelas::all();
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

        // Different schedule types
        $scheduleTypes = [
            [
                'jam_masuk' => '07:00:00',
                'jam_pulang' => '14:30:00',
                'keterangan' => 'Jadwal Reguler Pagi'
            ],
            [
                'jam_masuk' => '07:30:00',
                'jam_pulang' => '15:00:00',
                'keterangan' => 'Jadwal Reguler Siang'
            ]
        ];

        foreach ($classes as $classIndex => $kelas) {
            // Assign schedule type based on class (alternating)
            $scheduleType = $scheduleTypes[$classIndex % count($scheduleTypes)];

            foreach ($days as $day) {
                JadwalPelajaran::create([
                    'kelas_id' => $kelas->id,
                    'hari' => $day,
                    'jam_masuk' => $scheduleType['jam_masuk'],
                    'jam_pulang' => $scheduleType['jam_pulang'],
                    'keterangan' => $scheduleType['keterangan']
                ]);
            }
        }

        // Add special Saturday schedule for some classes
        $saturdayClasses = $classes->take(3); // First 3 classes have Saturday schedule

        foreach ($saturdayClasses as $kelas) {
            JadwalPelajaran::create([
                'kelas_id' => $kelas->id,
                'hari' => 'Sabtu',
                'jam_masuk' => '08:00:00',
                'jam_pulang' => '12:00:00',
                'keterangan' => 'Jadwal Sabtu (Ekstrakurikuler)'
            ]);
        }
    }
}
