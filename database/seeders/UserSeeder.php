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
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin2401'),
            'role' => 'admin'
        ]);

        // Create sample guru (teachers)
        $teachers = [
            ['name' => 'Budi Santoso, S.Pd', 'email' => 'budi@gmail.com'],
            ['name' => 'Siti Rahayu, S.Pd', 'email' => 'siti@gmail.com'],
            ['name' => 'Ahmad Wijaya, S.Pd', 'email' => 'ahmad@gmail.com'],
            ['name' => 'Maya Sari, S.Pd', 'email' => 'maya@gmail.com'],
            ['name' => 'Eko Prasetyo, S.Pd', 'email' => 'eko@gmail.com']
        ];

        foreach ($teachers as $teacher) {
            User::create([
                'name' => $teacher['name'],
                'email' => $teacher['email'],
                'password' => Hash::make('guru2401'),
                'role' => 'guru'
            ]);
        }
    }
}
