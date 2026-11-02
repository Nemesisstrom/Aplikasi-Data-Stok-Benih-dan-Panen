<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cache permission Spatie terlebih dahulu
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Daftar Hak Akses (Permissions)
        $permissions = [
            // Dashboard
            'view dashboard',
            'view rekap nasional',

            // Master Varietas
            'view varietas',
            'manage varietas',

            // Stok Benih
            'view stok benih',
            'create stok benih',
            'edit stok benih',
            'delete stok benih',

            // Catatan Panen
            'view catatan panen',
            'create catatan panen',
            'edit catatan panen',
            'delete catatan panen',

            // Management User & Role (Khusus Admin Pusat)
            'manage users',
            'manage roles',

            // Ekspor Laporan
            'export excel',
            'export pdf',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 2. Assign Permission ke Role Admin Pusat (Akses Penuh Seluruh Sistem)
        $roleAdmin = Role::firstOrCreate(['name' => 'admin_pusat']);
        $roleAdmin->syncPermissions(Permission::all());

        // 3. Assign Permission ke Role Petugas Provinsi / Daerah
        $rolePetugas = Role::firstOrCreate(['name' => 'petugas_provinsi']);
        $rolePetugas->syncPermissions([
            'view dashboard',
            'view varietas',
            'view stok benih',
            'create stok benih',
            'edit stok benih',
            'delete stok benih',
            'view catatan panen',
            'create catatan panen',
            'edit catatan panen',
            'delete catatan panen',
            'export excel',
            'export pdf',
        ]);

        // 4. Assign Permission ke Role Pimpinan (Monitoring, Read-Only & Ekspor)
        $rolePimpinan = Role::firstOrCreate(['name' => 'pimpinan']);
        $rolePimpinan->syncPermissions([
            'view dashboard',
            'view rekap nasional',
            'view varietas',
            'view stok benih',
            'view catatan panen',
            'export excel',
            'export pdf',
        ]);
    }
}
