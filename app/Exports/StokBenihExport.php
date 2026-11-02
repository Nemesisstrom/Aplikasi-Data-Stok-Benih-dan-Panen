<?php

namespace App\Exports;

use App\Models\StokBenih;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StokBenihExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $satkerId;
    protected $komoditas;

    public function __construct($satkerId = null, $komoditas = null)
    {
        $this->satkerId = $satkerId;
        $this->komoditas = $komoditas;
    }

    public function collection(): Enumerable
    {
        $query = StokBenih::with(['satker', 'varietas']);

        if ($this->satkerId) {
            $query->where('satker_id', $this->satkerId);
        }

        if ($this->komoditas) {
            $query->where('komoditas', $this->komoditas);
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Kode Satker',
            'Nama Satker BPSIP',
            'Komoditas',
            'Nama Varietas',
            'Kelas Benih',
            'Volume (Ton)',
            'Status Stok',
            'Nomor Sertifikat',
            'Catatan',
            'Tanggal Input',
        ];
    }

    public function map($stok): array
    {
        return [
            $stok->id,
            $stok->satker->kode_satker ?? '-',
            $stok->satker->nama_satker ?? '-',
            ucfirst($stok->komoditas),
            $stok->varietas->nama_varietas ?? '-',
            $stok->kelas_benih,
            $stok->volume_ton,
            str_replace('_', ' ', ucfirst($stok->status_stok)),
            $stok->nomor_sertifikat ?? '-',
            $stok->catatan ?? '-',
            $stok->created_at ? $stok->created_at->format('d/m/Y H:i') : '-',
        ];
    }

    /**
     * Tambahkan return type : ?array pada method styles()
     */
    public function styles(Worksheet $sheet): ?array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '198754']]
            ],
        ];
    }
}
