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
    protected ?int $satkerId;
    protected ?string $komoditas;

    public function __construct(?int $satkerId = null, ?string $komoditas = null)
    {
        $this->satkerId = $satkerId;
        $this->komoditas = $komoditas;
    }

    /**
     * Mengambil koleksi data stok benih berdasarkan filter
     */
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
            'Kelas Benih',
            'Volume (Ton)',
            'Status Stok',
            'Nomor Sertifikat',
            'Catatan',
            'Tanggal Input',
        ];
    }

    /**
     * Mapping Data per Baris
     */
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
