<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Stok Benih - BBPSIP</title>
    <style>
        @page {
            margin: 20px;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            font-size: 10px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            border-bottom: 2.5px solid #1b5e20;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        .header h2 {
            margin: 0;
            color: #1b5e20;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header h4 {
            margin: 3px 0 0 0;
            color: #333;
            font-size: 12px;
            font-weight: normal;
        }
        .header p {
            margin: 4px 0 0 0;
            font-size: 10px;
            color: #666;
        }
        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .table-data th {
            background-color: #1b5e20;
            color: #ffffff;
            font-weight: bold;
            text-transform: uppercase;
            padding: 7px 5px;
            font-size: 9.5px;
            border: 1px solid #1b5e20;
            text-align: center;
        }
        .table-data td {
            padding: 6px 5px;
            border: 1px solid #dcdcdc;
            vertical-align: middle;
        }
        .table-data tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .table-footer {
            background-color: #e8f5e9;
            font-weight: bold;
        }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .text-capitalize { text-transform: capitalize; }

        .badge {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 8.5px;
            font-weight: bold;
        }
        .badge-padi {
            background-color: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #a5d6a7;
        }
        .badge-jagung {
            background-color: #fff8e1;
            color: #f57f17;
            border: 1px solid #ffe082;
        }
        .badge-kelas {
            background-color: #e3f2fd;
            color: #1565c0;
            border: 1px solid #90caf9;
        }
        .badge-status {
            background-color: #f1f8e9;
            color: #33691e;
        }

        .footer {
            margin-top: 25px;
            width: 100%;
        }
        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }
        .footer-table td {
            border: none;
            padding: 0;
        }
    </style>
</head>
<body>

    <!-- Header Laporan / Kop Surat -->
    <div class="header">
        <h2>BALAI BESAR PENERAPAN STANDAR INSTRUMEN PERTANIAN (BBPSIP)</h2>
        <h4>LAPORAN REKAPITULASI STOK BENIH KELAS BS, FS, SS, DAN ES</h4>
        <p>Tanggal Cetak: {{ date('d F Y H:i') }} WIB | Dicetak Oleh: {{ auth()->user()->name }}</p>
    </div>

    <!-- Tabel Data Stok Benih -->
    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 4%;">NO</th>
                <th style="width: 10%;">KODE SATKER</th>
                <th style="width: 18%;">NAMA BPSIP / SATKER</th>
                <th style="width: 8%;">KOMODITAS</th>
                <th style="width: 14%;">VARIETAS</th>
                <th style="width: 6%;">KELAS</th>
                <th style="width: 10%;">VOLUME (TON)</th>
                <th style="width: 14%;">STATUS STOK</th>
                <th style="width: 16%;">NO. SERTIFIKAT</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalVolume = 0;
            @endphp
            @forelse($stokBenihs as $index => $stok)
                @php
                    $totalVolume += $stok->volume_ton;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $stok->satker->kode_satker ?? '-' }}</td>
                    <td>{{ $stok->satker->nama_satker ?? '-' }}</td>
                    <td class="text-center">
                        @if($stok->komoditas == 'padi')
                            <span class="badge badge-padi">PADI</span>
                        @else
                            <span class="badge badge-jagung">JAGUNG</span>
                        @endif
                    </td>
                    <td>{{ $stok->varietas->nama_varietas ?? '-' }}</td>
                    <td class="text-center">
                        <span class="badge badge-kelas">{{ $stok->kelas_benih }}</span>
                    </td>
                    <td class="text-end" style="font-weight: bold; color: #1b5e20;">
                        {{ number_format($stok->volume_ton, 2, ',', '.') }}
                    </td>
                    <td class="text-center">
                        @switch($stok->status_stok)
                            @case('tersedia_tersertifikasi')
                                <span class="badge badge-status">Tersedia (Sertifikat)</span>
                                @break
                            @case('proses_sertifikasi')
                                <span class="badge badge-status">Proses Sertifikasi</span>
                                @break
                            @case('prosesing')
                                <span class="badge badge-status">Prosesing</span>
                                @break
                            @default
                                <span class="badge badge-status">Persiapan Panen</span>
                        @endswitch
                    </td>
                    <td>{{ $stok->nomor_sertifikat ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center" style="padding: 15px; color: #777;">
                        Belum ada data stok benih yang tersedia.
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if(count($stokBenihs) > 0)
        <tfoot>
            <tr class="table-footer">
                <td colspan="6" class="text-end" style="padding: 8px;">TOTAL KESELURUHAN STOK BENIH:</td>
                <td class="text-end" style="color: #1b5e20;">{{ number_format($totalVolume, 2, ',', '.') }} Ton</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
        @endif
    </table>

    <!-- Footer Lembar Tanda Tangan / Informasi Dokumen -->
    <div class="footer">
        <table class="footer-table">
            <tr>
                <td style="width: 65%; font-size: 8.5px; color: #777; vertical-align: bottom;">
                    * Dokumen resmi hasil rekapitulasi Sistem Informasi Stok Benih & Panen BBPSIP.
                </td>
                <td style="width: 35%; text-align: center;">
                    <p style="margin-bottom: 45px;">
                        Mengetahui,<br>
                        <strong>Penanggung Jawab Satker BPSIP</strong>
                    </p>
                    <p style="margin: 0;">( ___________________________ )</p>
                    <p style="margin-top: 2px; font-size: 8.5px; color: #555;">NIP. .................................................</p>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
