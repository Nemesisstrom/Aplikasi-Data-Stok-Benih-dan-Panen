<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VarietasSeeder extends Seeder
{
    public function run(): void
    {
        // Varietas Padi
        $padiVarietas = [
            'Inpari 32', 'Inpari 30', 'Inpari 16', 'Inpari 42', 'Inpari 43',
            'Inpari 46', 'Inpari 47', 'Inpari 48', 'Inpari 49', 'Inpari IR Nutrizinc',
            'Inpago 13', 'Inpago 12', 'Inpago 9', 'Mekongga', 'Ciherang',
            'Cigeulis', 'Cakrabuana', 'Padjadjaran', 'Biosalin 1', 'Biosalin 2',
            'Bioprima', 'Respati', 'Mantap', 'Logawa', 'Anak Daro', 'Batang Piaman'
        ];

        foreach ($padiVarietas as $var) {
            DB::table('varietas')->insert([
                'komoditas' => 'padi',
                'nama_varietas' => $var,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Varietas Jagung
        $jagungVarietas = [
            'Sukmaraga', 'Bisma', 'Jakarin 1', 'Lamuru', 'Pulut Uri 1'
        ];

        foreach ($jagungVarietas as $var) {
            DB::table('varietas')->insert([
                'komoditas' => 'jagung',
                'nama_varietas' => $var,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
