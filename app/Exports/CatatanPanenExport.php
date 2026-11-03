<?php

namespace App\Exports;

use App\Models\CatatanPanen;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CatatanPanenExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected ?int $satkerId;
    protected ?string $komoditas;

    public function __construct(?int $satkerId = null, ?string $komoditas = null)
    {
        $this->satkerId = $satkerId;
        $this->komoditas = $komoditas;
    }

    /**
     * Mengambil koleksi data catatan panen berdasarkan filter
     */
    public function collection(): Enumerable
    {
        $query = CatatanPanen::with(['satker', 'varietas', 'user']);

        if ($this->satkerId) {
            $query->where('satker_id', $this->satkerId);
        }

        if ($this->komoditas) {
            $query->where('komoditas', $this->komoditas);
        }

        return $query->latest('tanggal_panen')->get();
    }

    /**
     * Header Kolom Excel
     */
    public function headings(): array
    {
        return [
            'ID',
            'Kode Satker',
            'Nama Satker BPSIP',
            'Komoditas',
            'Nama Varietas',
            'Luas Lahan (Ha)',
            'Jumlah Panen (Ton)',
            'Tanggal Panen',
            'Lokasi Lahan',
            'Petugas Input',
            'Catatan',
        ];
    }

    /**
     * Mapping Data per Baris
     */
    public function map($panen): array
    {
        return [
            $panen->id,
            $panen->satker->kode_satker ?? '-',
            $panen->satker->nama_satker ?? '-',
            ucfirst($panen->komoditas),
            $panen->varietas->nama_varietas ?? '-',
            $panen->luas_lahan_ha ?? $panen->luas_lahan_hektar ?? 0,
            $panen->jumlah_panen_ton,
            \Carbon\Carbon::parse($panen->tanggal_panen)->format('d/m/Y'),
            $panen->lokasi_lahan ?? '-',
            $panen->user->name ?? '-',
            $panen->catatan ?? '-',
        ];
    }

    /**
     * Styling Spreadsheet dengan return type : ?array (Kompatibel PHP 8.4)
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
