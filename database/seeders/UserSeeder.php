<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@diancahayaprima.com'],
            [
                'username' => 'admin_dcp',
                'nama_lengkap' => 'Admin Penagihan',
                'password' => Hash::make('password123'),
                'role' => 'ADMIN',
            ]
        );
        $admin->syncRoles(['Admin Penagihan']);

        $supervisor = User::firstOrCreate(
            ['email' => 'spv@diancahayaprima.com'],
            [
                'username' => 'spv_dcp',
                'nama_lengkap' => 'Supervisor Lapangan',
                'password' => Hash::make('password123'),
                'role' => 'SUPERVISOR',
            ]
        );
        $supervisor->syncRoles(['Supervisor']);

        $worker = User::firstOrCreate(
            ['email' => 'worker@diancahayaprima.com'],
            [
                'username' => 'worker_dcp',
                'nama_lengkap' => 'Worker Lapangan',
                'password' => Hash::make('password123'),
                'role' => 'WORKER',
            ]
        );
        $worker->syncRoles(['Worker']);
    }
}
