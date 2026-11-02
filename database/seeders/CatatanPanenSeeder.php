<?php

namespace Database\Seeders;

use App\Models\Satker;
use App\Models\Varietas;
use App\Models\User;
use App\Models\CatatanPanen;
use Illuminate\Database\Seeder;

class CatatanPanenSeeder extends Seeder
{
    public function run(): void
    {
        $aceh = Satker::where('kode_satker', 'BPSIP-11')->first();
        $sumut = Satker::where('kode_satker', 'BPSIP-12')->first();

        $inpari32 = Varietas::where('nama_varietas', 'Inpari 32')->first();
        $sukmaraga = Varietas::where('nama_varietas', 'Sukmaraga')->first();

        $petugasAceh = User::where('email', 'bpsip.aceh@pertanian.go.id')->first();
        $petugasSumut = User::where('email', 'bpsip.sumut@pertanian.go.id')->first();

        $panenSample = [
            [
                'satker_id' => $aceh->id,
                'varietas_id' => $inpari32?->id,
                'user_id' => $petugasAceh?->id,
                'komoditas' => 'padi',
                'luas_lahan_hektar' => 3.50,
                'jumlah_panen_ton' => 21.700,
                'tanggal_panen' => '2024-11-20',
                'catatan' => 'Panen Ubinan Kebun Percobaan Aceh',
            ],
            [
                'satker_id' => $sumut->id,
                'varietas_id' => $sukmaraga?->id,
                'user_id' => $petugasSumut?->id,
                'komoditas' => 'jagung',
                'luas_lahan_hektar' => 2.00,
                'jumlah_panen_ton' => 12.400,
                'tanggal_panen' => '2024-12-01',
                'catatan' => 'Hasil panen benih jagung komposit',
            ],
        ];

        foreach ($panenSample as $panen) {
            CatatanPanen::create($panen);
        }
    }
}
