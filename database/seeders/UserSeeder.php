<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@sekolah.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        // Create sample guru (teachers)
        $teachers = [
            ['name' => 'Budi Santoso, S.Pd', 'email' => 'budi@sekolah.com'],
            ['name' => 'Siti Rahayu, S.Pd', 'email' => 'siti@sekolah.com'],
            ['name' => 'Ahmad Wijaya, S.Pd', 'email' => 'ahmad@sekolah.com'],
            ['name' => 'Maya Sari, S.Pd', 'email' => 'maya@sekolah.com'],
            ['name' => 'Eko Prasetyo, S.Pd', 'email' => 'eko@sekolah.com']
        ];

        foreach ($teachers as $teacher) {
            User::create([
                'name' => $teacher['name'],
                'email' => $teacher['email'],
                'password' => Hash::make('guru123'),
                'role' => 'guru'
            ]);
        }
    }
}
