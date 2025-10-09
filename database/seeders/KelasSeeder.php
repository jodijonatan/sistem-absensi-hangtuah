<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kelas;
use App\Models\User;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get teachers (guru) for wali kelas assignment
        $teachers = User::where('role', 'guru')->get();

        $classes = [
            ['nama_kelas' => '10-A IPS'],
            ['nama_kelas' => '10-B IPA'],
            ['nama_kelas' => '11-A IPA'],
            ['nama_kelas' => '11-B IPA'],
            ['nama_kelas' => '11-A IPS'],
            ['nama_kelas' => '12-A IPA'],
            ['nama_kelas' => '12-B IPA'],
            ['nama_kelas' => '12-A IPS']
        ];

        foreach ($classes as $index => $class) {
            Kelas::create([
                'nama_kelas' => $class['nama_kelas'],
                'wali_kelas_id' => $teachers->get($index % $teachers->count())?->id
            ]);
        }
    }
}
