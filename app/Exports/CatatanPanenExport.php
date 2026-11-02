<?php

namespace App\Exports;

use App\Models\CatatanPanen;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class CatatanPanenExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $satkerId;

    public function __construct($satkerId = null)
    {
        $this->satkerId = $satkerId;
    }

    /**
     * Query data catatan panen
     */
    public function query()
    {
        $query = CatatanPanen::with(['satker', 'varietas']);

        // Jika dipanggil oleh Petugas Daerah, filter sesuai satker_id
        if ($this->satkerId) {
            $query->where('satker_id', $this->satkerId);
        }

        return $query->latest('tanggal_panen');
    }

    /**
     * Header Kolom Excel
     */
    public function headings(): array
    {
        return [
            'NO',
            'KODE SATKER',
            'NAMA BPSIP / SATKER',
            'TANGGAL PANEN',
            'KOMODITAS',
            'VARIETAS',
            'LUAS LAHAN (HA)',
            'HASIL PANEN (TON)',
            'LOKASI LAHAN',
            'CATATAN',
        ];
    }

    /**
     * Mapping Baris Data Excel
     */
    public function map($panen): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $panen->satker->kode_satker ?? '-',
            $panen->satker->nama_satker ?? '-',
            $panen->tanggal_panen ? date('d/m/Y', strtotime($panen->tanggal_panen)) : '-',
            strtoupper($panen->komoditas),
            $panen->varietas->nama_varietas ?? '-',
            number_format($panen->luas_lahan_ha, 2, ',', '.'),
            number_format($panen->jumlah_panen_ton, 2, ',', '.'),
            $panen->lokasi_lahan ?? '-',
            $panen->catatan ?? '-',
        ];
    }

    /**
     * Styling Header & Border Excel
     */
    public function styles(Worksheet $sheet)
    {
        // Style Header (Baris 1) - Warna Hijau BBPSIP
        $sheet->getStyle('A1:J1')->applyFromArray([
            'font' => [
                'bold'  => true,
                'color' => ['argb' => 'FFFFFF'],
                'size'  => 11,
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['argb' => '1B5E20'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Border & Alignment untuk seluruh sel data
        $highestRow = $sheet->getHighestRow();
        if ($highestRow > 1) {
            $sheet->getStyle("A1:J{$highestRow}")->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color'       => ['argb' => 'CCCCCC'],
                    ],
                ],
            ]);

            // Alignment Tengah untuk No, Kode, Tanggal, Komoditas
            $sheet->getStyle("A2:A{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("B2:B{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("D2:E{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Alignment Kanan untuk Luas Lahan (Ha) dan Hasil Panen (Ton)
            $sheet->getStyle("G2:H{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        }

        return [];
    }
}
