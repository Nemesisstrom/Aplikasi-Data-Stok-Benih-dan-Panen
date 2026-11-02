<?php

namespace Database\Seeders;

use App\Models\Satker;
use App\Models\Varietas;
use App\Models\StokBenih;
use Illuminate\Database\Seeder;

class StokBenihSeeder extends Seeder
{
    public function run(): void
    {
        $aceh = Satker::where('kode_satker', 'BPSIP-11')->first();
        $sumut = Satker::where('kode_satker', 'BPSIP-12')->first();
        $riau = Satker::where('kode_satker', 'BPSIP-14')->first();

        $inpari32 = Varietas::where('nama_varietas', 'Inpari 32')->first();
        $inpago13 = Varietas::where('nama_varietas', 'Inpago 13')->first();
        $sukmaraga = Varietas::where('nama_varietas', 'Sukmaraga')->first();
        $jakarin1 = Varietas::where('nama_varietas', 'Jakarin 1')->first();

        $laporanDate = '2024-12-12';

        $stokSample = [
            // BPSIP Aceh - Padi SS Target 25 Ton
            [
                'satker_id' => $aceh->id,
                'varietas_id' => $inpari32->id,
                'komoditas' => 'padi',
                'kelas_benih' => 'SS',
                'status_stok' => 'target',
                'volume_ton' => 25.000,
                'estimasi_panen' => null,
                'tanggal_laporan' => $laporanDate,
            ],
            // BPSIP Aceh - Padi SS Tersedia (18 Ton)
            [
                'satker_id' => $aceh->id,
                'varietas_id' => $inpari32->id,
                'komoditas' => 'padi',
                'kelas_benih' => 'SS',
                'status_stok' => 'tersedia_tersertifikasi',
                'volume_ton' => 18.000,
                'estimasi_panen' => null,
                'tanggal_laporan' => $laporanDate,
            ],
            // BPSIP Aceh - Jagung FS Persiapan Panen (6 Ton)
            [
                'satker_id' => $aceh->id,
                'varietas_id' => $sukmaraga->id,
                'komoditas' => 'jagung',
                'kelas_benih' => 'FS',
                'status_stok' => 'persiapan_panen',
                'volume_ton' => 6.000,
                'estimasi_panen' => '20/6/2024',
                'tanggal_laporan' => $laporanDate,
            ],
            // BPSIP Sumut - Padi FS Tersedia Inpago 13 (2.32 Ton)
            [
                'satker_id' => $sumut->id,
                'varietas_id' => $inpago13->id,
                'komoditas' => 'padi',
                'kelas_benih' => 'FS',
                'status_stok' => 'tersedia_tersertifikasi',
                'volume_ton' => 2.320,
                'estimasi_panen' => null,
                'tanggal_laporan' => $laporanDate,
            ],
            // BPSIP Riau - Jagung SS Prosesing Jakarin 1 (3 Ton)
            [
                'satker_id' => $riau->id,
                'varietas_id' => $jakarin1->id,
                'komoditas' => 'jagung',
                'kelas_benih' => 'SS',
                'status_stok' => 'prosesing',
                'volume_ton' => 3.000,
                'estimasi_panen' => 'Minggu ke 3 Desember 2024',
                'tanggal_laporan' => $laporanDate,
            ],
        ];

        foreach ($stokSample as $stok) {
            StokBenih::create($stok);
        }
    }
}
