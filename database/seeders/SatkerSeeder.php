<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SatkerSeeder extends Seeder
{
    public function run(): void
    {
        $satkers = [
            ['kode_satker' => 'BPSIP-11', 'nama_satker' => 'BPSIP Aceh', 'provinsi' => 'Aceh'],
            ['kode_satker' => 'BPSIP-12', 'nama_satker' => 'BPSIP Sumatera Utara', 'provinsi' => 'Sumatera Utara'],
            ['kode_satker' => 'BPSIP-13', 'nama_satker' => 'BPSIP Sumatera Barat', 'provinsi' => 'Sumatera Barat'],
            ['kode_satker' => 'BPSIP-14', 'nama_satker' => 'BPSIP Riau', 'provinsi' => 'Riau'],
            ['kode_satker' => 'BPSIP-15', 'nama_satker' => 'BPSIP Jambi', 'provinsi' => 'Jambi'],
            ['kode_satker' => 'BPSIP-16', 'nama_satker' => 'BPSIP Sumatera Selatan', 'provinsi' => 'Sumatera Selatan'],
            ['kode_satker' => 'BPSIP-17', 'nama_satker' => 'BPSIP Bengkulu', 'provinsi' => 'Bengkulu'],
            ['kode_satker' => 'BPSIP-18', 'nama_satker' => 'BPSIP Lampung', 'provinsi' => 'Lampung'],
            ['kode_satker' => 'BPSIP-19', 'nama_satker' => 'BPSIP Bangka Belitung', 'provinsi' => 'Kepulauan Bangka Belitung'],
            ['kode_satker' => 'BPSIP-21', 'nama_satker' => 'BPSIP Kepulauan Riau', 'provinsi' => 'Kepulauan Riau'],
            ['kode_satker' => 'BPSIP-31', 'nama_satker' => 'BPSIP DKI Jakarta', 'provinsi' => 'DKI Jakarta'],
            ['kode_satker' => 'BPSIP-32', 'nama_satker' => 'BPSIP Jawa Barat', 'provinsi' => 'Jawa Barat'],
            ['kode_satker' => 'BPSIP-33', 'nama_satker' => 'BPSIP Jawa Tengah', 'provinsi' => 'Jawa Tengah'],
            ['kode_satker' => 'BPSIP-34', 'nama_satker' => 'BPSIP DI Yogyakarta', 'provinsi' => 'DI Yogyakarta'],
            ['kode_satker' => 'BPSIP-35', 'nama_satker' => 'BPSIP Jawa Timur', 'provinsi' => 'Jawa Timur'],
            ['kode_satker' => 'BPSIP-36', 'nama_satker' => 'BPSIP Banten', 'provinsi' => 'Banten'],
            ['kode_satker' => 'BPSIP-51', 'nama_satker' => 'BPSIP Bali', 'provinsi' => 'Bali'],
            ['kode_satker' => 'BPSIP-52', 'nama_satker' => 'BPSIP NTB', 'provinsi' => 'Nusa Tenggara Barat'],
            ['kode_satker' => 'BPSIP-53', 'nama_satker' => 'BPSIP NTT', 'provinsi' => 'Nusa Tenggara Timur'],
            ['kode_satker' => 'BPSIP-61', 'nama_satker' => 'BPSIP Kalimantan Barat', 'provinsi' => 'Kalimantan Barat'],
            ['kode_satker' => 'BPSIP-62', 'nama_satker' => 'BPSIP Kalimantan Tengah', 'provinsi' => 'Kalimantan Tengah'],
            ['kode_satker' => 'BPSIP-63', 'nama_satker' => 'BPSIP Kalimantan Selatan', 'provinsi' => 'Kalimantan Selatan'],
            ['kode_satker' => 'BPSIP-64', 'nama_satker' => 'BPSIP Kalimantan Timur', 'provinsi' => 'Kalimantan Timur'],
            ['kode_satker' => 'BPSIP-71', 'nama_satker' => 'BPSIP Sulawesi Utara', 'provinsi' => 'Sulawesi Utara'],
            ['kode_satker' => 'BPSIP-72', 'nama_satker' => 'BPSIP Sulawesi Tengah', 'provinsi' => 'Sulawesi Tengah'],
            ['kode_satker' => 'BPSIP-73', 'nama_satker' => 'BPSIP Sulawesi Selatan', 'provinsi' => 'Sulawesi Selatan'],
            ['kode_satker' => 'BPSIP-74', 'nama_satker' => 'BPSIP Sulawesi Tenggara', 'provinsi' => 'Sulawesi Tenggara'],
            ['kode_satker' => 'BPSIP-75', 'nama_satker' => 'BPSIP Gorontalo', 'provinsi' => 'Gorontalo'],
            ['kode_satker' => 'BPSIP-76', 'nama_satker' => 'BPSIP Sulawesi Barat', 'provinsi' => 'Sulawesi Barat'],
            ['kode_satker' => 'BPSIP-81', 'nama_satker' => 'BPSIP Maluku', 'provinsi' => 'Maluku'],
            ['kode_satker' => 'BPSIP-82', 'nama_satker' => 'BPSIP Maluku Utara', 'provinsi' => 'Maluku Utara'],
            ['kode_satker' => 'BPSIP-91', 'nama_satker' => 'BPSIP Papua Barat', 'provinsi' => 'Papua Barat'],
            ['kode_satker' => 'BPSIP-93', 'nama_satker' => 'BPSIP Papua', 'provinsi' => 'Papua'],
        ];

        DB::table('satkers')->insert($satkers);
    }
}
