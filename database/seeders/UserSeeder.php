<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Satker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin Pusat BBPSIP
        $admin = User::firstOrCreate(
            ['email' => 'admin@pertanian.go.id'],
            [
                'name' => 'Administrator Pusat BBPSIP',
                'password' => Hash::make('admin123'),
                'satker_id' => null,
            ]
        );
        $admin->assignRole('admin_pusat');

        // 2. Akun Pimpinan / Kepala BBPSIP
        $pimpinan = User::firstOrCreate(
            ['email' => 'pimpinan@pertanian.go.id'],
            [
                'name' => 'Kepala BBPSIP',
                'password' => Hash::make('password'),
                'satker_id' => null,
            ]
        );
        $pimpinan->assignRole('pimpinan');

        // 3. Akun Petugas Satker BPSIP Aceh
        $satkerAceh = Satker::where('kode_satker', 'BPSIP-11')->first();
        if ($satkerAceh) {
            $petugasAceh = User::firstOrCreate(
                ['email' => 'bpsip.aceh@pertanian.go.id'],
                [
                    'name' => 'Petugas BPSIP Aceh',
                    'password' => Hash::make('password'),
                    'satker_id' => $satkerAceh->id,
                ]
            );
            $petugasAceh->assignRole('petugas_provinsi');
        }

        // 4. Akun Petugas Satker BPSIP Sumut
        $satkerSumut = Satker::where('kode_satker', 'BPSIP-12')->first();
        if ($satkerSumut) {
            $petugasSumut = User::firstOrCreate(
                ['email' => 'bpsip.sumut@pertanian.go.id'],
                [
                    'name' => 'Petugas BPSIP Sumatera Utara',
                    'password' => Hash::make('password'),
                    'satker_id' => $satkerSumut->id,
                ]
            );
            $petugasSumut->assignRole('petugas_provinsi');
        }
    }
}
