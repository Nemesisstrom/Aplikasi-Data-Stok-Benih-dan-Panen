<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\ProvinsiSeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use Database\Seeders\SatkerSeeder;
use Database\Seeders\VarietasSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\StokBenihSeeder;
use Database\Seeders\CatatanPanenSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            ProvinsiSeeder::class,          // 1. Master 38 Provinsi
            RoleAndPermissionSeeder::class, // 2. Spatie Role & Permission
            SatkerSeeder::class,             // 3. Master 33 Satker BPSIP
            VarietasSeeder::class,           // 4. Master Varietas Padi & Jagung
            UserSeeder::class,               // 5. Akun Administrator, Petugas & Pimpinan
            StokBenihSeeder::class,          // 6. Data Dummy Stok Benih
            CatatanPanenSeeder::class,       // 7. Data Dummy Hasil Panen
        ]);
    }
}
