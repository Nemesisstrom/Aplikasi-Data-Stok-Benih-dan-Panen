<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinsiSeeder extends Seeder
{
    public function run(): void
    {
        $provinsis = [
            ['kode_provinsi' => '11', 'nama_provinsi' => 'Aceh', 'ibu_kota' => 'Banda Aceh'],
            ['kode_provinsi' => '12', 'nama_provinsi' => 'Sumatera Utara', 'ibu_kota' => 'Medan'],
            ['kode_provinsi' => '13', 'nama_provinsi' => 'Sumatera Barat', 'ibu_kota' => 'Padang'],
            ['kode_provinsi' => '14', 'nama_provinsi' => 'Riau', 'ibu_kota' => 'Pekanbaru'],
            ['kode_provinsi' => '15', 'nama_provinsi' => 'Jambi', 'ibu_kota' => 'Jambi'],
            ['kode_provinsi' => '16', 'nama_provinsi' => 'Sumatera Selatan', 'ibu_kota' => 'Palembang'],
            ['kode_provinsi' => '17', 'nama_provinsi' => 'Bengkulu', 'ibu_kota' => 'Bengkulu'],
            ['kode_provinsi' => '18', 'nama_provinsi' => 'Lampung', 'ibu_kota' => 'Bandar Lampung'],
            ['kode_provinsi' => '19', 'nama_provinsi' => 'Kepulauan Bangka Belitung', 'ibu_kota' => 'Pangkal Pinang'],
            ['kode_provinsi' => '21', 'nama_provinsi' => 'Kepulauan Riau', 'ibu_kota' => 'Tanjung Pinang'],
            ['kode_provinsi' => '31', 'nama_provinsi' => 'DKI Jakarta', 'ibu_kota' => 'Jakarta'],
            ['kode_provinsi' => '32', 'nama_provinsi' => 'Jawa Barat', 'ibu_kota' => 'Bandung'],
            ['kode_provinsi' => '33', 'nama_provinsi' => 'Jawa Tengah', 'ibu_kota' => 'Semarang'],
            ['kode_provinsi' => '34', 'nama_provinsi' => 'DI Yogyakarta', 'ibu_kota' => 'Yogyakarta'],
            ['kode_provinsi' => '35', 'nama_provinsi' => 'Jawa Timur', 'ibu_kota' => 'Surabaya'],
            ['kode_provinsi' => '36', 'nama_provinsi' => 'Banten', 'ibu_kota' => 'Serang'],
            ['kode_provinsi' => '51', 'nama_provinsi' => 'Bali', 'ibu_kota' => 'Denpasar'],
            ['kode_provinsi' => '52', 'nama_provinsi' => 'Nusa Tenggara Barat', 'ibu_kota' => 'Mataram'],
            ['kode_provinsi' => '53', 'nama_provinsi' => 'Nusa Tenggara Timur', 'ibu_kota' => 'Kupang'],
            ['kode_provinsi' => '61', 'nama_provinsi' => 'Kalimantan Barat', 'ibu_kota' => 'Pontianak'],
            ['kode_provinsi' => '62', 'nama_provinsi' => 'Kalimantan Tengah', 'ibu_kota' => 'Palangka Raya'],
            ['kode_provinsi' => '63', 'nama_provinsi' => 'Kalimantan Selatan', 'ibu_kota' => 'Banjarbaru'],
            ['kode_provinsi' => '64', 'nama_provinsi' => 'Kalimantan Timur', 'ibu_kota' => 'Samarinda'],
            ['kode_provinsi' => '65', 'nama_provinsi' => 'Kalimantan Utara', 'ibu_kota' => 'Tanjung Selor'],
            ['kode_provinsi' => '71', 'nama_provinsi' => 'Sulawesi Utara', 'ibu_kota' => 'Manado'],
            ['kode_provinsi' => '72', 'nama_provinsi' => 'Sulawesi Tengah', 'ibu_kota' => 'Palu'],
            ['kode_provinsi' => '73', 'nama_provinsi' => 'Sulawesi Selatan', 'ibu_kota' => 'Makassar'],
            ['kode_provinsi' => '74', 'nama_provinsi' => 'Sulawesi Tenggara', 'ibu_kota' => 'Kendari'],
            ['kode_provinsi' => '75', 'nama_provinsi' => 'Gorontalo', 'ibu_kota' => 'Gorontalo'],
            ['kode_provinsi' => '76', 'nama_provinsi' => 'Sulawesi Barat', 'ibu_kota' => 'Mamuju'],
            ['kode_provinsi' => '81', 'nama_provinsi' => 'Maluku', 'ibu_kota' => 'Ambon'],
            ['kode_provinsi' => '82', 'nama_provinsi' => 'Maluku Utara', 'ibu_kota' => 'Sofifi'],
            ['kode_provinsi' => '91', 'nama_provinsi' => 'Papua', 'ibu_kota' => 'Jayapura'],
            ['kode_provinsi' => '92', 'nama_provinsi' => 'Papua Barat', 'ibu_kota' => 'Manokwari'],
            ['kode_provinsi' => '93', 'nama_provinsi' => 'Papua Selatan', 'ibu_kota' => 'Merauke'],
            ['kode_provinsi' => '94', 'nama_provinsi' => 'Papua Tengah', 'ibu_kota' => 'Nabire'],
            ['kode_provinsi' => '95', 'nama_provinsi' => 'Papua Pegunungan', 'ibu_kota' => 'Jayawijaya'],
            ['kode_provinsi' => '96', 'nama_provinsi' => 'Papua Barat Daya', 'ibu_kota' => 'Sorong'],
        ];

        foreach ($provinsis as $prov) {
            DB::table('provinsis')->updateOrInsert(
                ['kode_provinsi' => $prov['kode_provinsi']],
                array_merge($prov, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }
}
