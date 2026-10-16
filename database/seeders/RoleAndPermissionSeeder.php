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
            'manage profile',
            'view khs',
            'manage khs',
            'manage kr',
            'view tahap',
            'manage tahap',
            'create pk',
            'edit pk',
            'view pk',
            'view pk details',
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
        // but we assign all permissions explicitly for consistency and auditability.
        $adminRole = Role::firstOrCreate(['name' => 'Admin Penagihan']);
        $adminRole->syncPermissions($permissions);

        // Role: Supervisor (Pengawas / Verifikator)
        // - Tidak bisa memodifikasi KHS (hanya view)
        // - Hak penuh modifikasi modul KR dan PK (termasuk foto)
        // - Bisa view Tahap & export ZIP
        $supervisorRole = Role::firstOrCreate(['name' => 'Supervisor']);
        $supervisorRole->syncPermissions([
            'view khs',
            'manage kr',
            'manage profile',
            'view tahap',
            'create pk',
            'edit pk',
            'view pk',
            'view pk details',
            'upload foto',
            'delete foto',
            'export zip',
        ]);

        // Role: Worker (Petugas Lapangan / Vendor)
        // - Hanya bisa melihat daftar PK dan mengunggah foto
        // - Tidak bisa menghapus foto maupun memodifikasi data apapun
        $workerRole = Role::firstOrCreate(['name' => 'Worker']);
        $workerRole->syncPermissions([
            'view pk',
            'upload foto',
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
        $adminUser->syncRoles($adminRole);

        $supervisorUser = User::firstOrCreate([
            'email' => 'supervisor@fielddoc.test'
        ], [
            'username' => 'supervisor',
            'nama_lengkap' => 'Sample Supervisor',
            'password' => Hash::make('password'),
            'role' => 'SUPERVISOR'
        ]);
        $supervisorUser->syncRoles($supervisorRole);

        $workerUser = User::firstOrCreate([
            'email' => 'worker@fielddoc.test'
        ], [
            'username' => 'worker',
            'nama_lengkap' => 'Sample Worker',
            'password' => Hash::make('password'),
            'role' => 'WORKER'
        ]);
        $workerUser->syncRoles($workerRole);
    }
}
