<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Create Permissions
        $permissions = [
            'manage users',
            'manage khs',
            'manage kr',
            'manage tahap',
            'create pk',
            'edit pk',
            'view pk',
            'upload foto',
            'delete foto',
            'export zip',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 2. Create Roles and Assign Permissions
        // Role: Admin Penagihan (Architect)
        // Gets all permissions automatically via Gate::before in AppServiceProvider, 
        // but we can still create the role here.
        $adminRole = Role::firstOrCreate(['name' => 'Admin Penagihan']);

        // Role: Supervisor (Pengawas / Verifikator)
        $supervisorRole = Role::firstOrCreate(['name' => 'Supervisor']);
        $supervisorRole->givePermissionTo([
            'create pk',
            'edit pk',
            'view pk',
            'export zip'
        ]);

        // Role: Worker (Petugas Lapangan / Vendor)
        $workerRole = Role::firstOrCreate(['name' => 'Worker']);
        $workerRole->givePermissionTo([
            'view pk',
            'upload foto',
            'delete foto'
        ]);

        // 3. Create Sample Users and Assign Roles
        $adminUser = User::firstOrCreate([
            'email' => 'admin@fielddoc.test'
        ], [
            'username' => 'admin',
            'nama_lengkap' => 'Sample Admin',
            'password' => Hash::make('password'),
            'role' => 'ADMIN'
        ]);
        $adminUser->assignRole($adminRole);

        $supervisorUser = User::firstOrCreate([
            'email' => 'supervisor@fielddoc.test'
        ], [
            'username' => 'supervisor',
            'nama_lengkap' => 'Sample Supervisor',
            'password' => Hash::make('password'),
            'role' => 'SUPERVISOR'
        ]);
        $supervisorUser->assignRole($supervisorRole);

        $workerUser = User::firstOrCreate([
            'email' => 'worker@fielddoc.test'
        ], [
            'username' => 'worker',
            'nama_lengkap' => 'Sample Worker',
            'password' => Hash::make('password'),
            'role' => 'WORKER'
        ]);
        $workerUser->assignRole($workerRole);
    }
}
