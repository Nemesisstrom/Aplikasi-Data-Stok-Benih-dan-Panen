<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Catatan Panen - BBPSIP</title>
    <style>
        @page {
            margin: 20px;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            font-size: 11px;
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
            font-size: 13px;
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
            padding: 8px 6px;
            font-size: 10px;
            border: 1px solid #1b5e20;
            text-align: center;
        }
        .table-data td {
            padding: 7px 6px;
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
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
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
    </style>
</head>
<body>

    <!-- Header Laporan / Kop Surat -->
    <div class="header">
        <h2>BALAI BESAR PENERAPAN STANDAR INSTRUMEN PERTANIAN (BBPSIP)</h2>
        <h4>LAPORAN REALISASI CATATAN PANEN KOMODITAS PADI & JAGUNG</h4>
        <p>Tanggal Cetak: {{ date('d F Y H:i') }} WIB | Dicetak Oleh: {{ auth()->user()->name }}</p>
    </div>

    <!-- Tabel Data Catatan Panen -->
    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 4%;">NO</th>
                <th style="width: 12%;">KODE SATKER</th>
                <th style="width: 20%;">NAMA BPSIP / SATKER</th>
                <th style="width: 10%;">TGL PANEN</th>
                <th style="width: 10%;">KOMODITAS</th>
                <th style="width: 14%;">VARIETAS</th>
                <th style="width: 10%;">LUAS LAHAN (HA)</th>
                <th style="width: 10%;">HASIL PANEN (TON)</th>
                <th style="width: 10%;">LOKASI LAHAN</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalLuas = 0;
                $totalPanen = 0;
            @endphp
            @forelse($catatanPanens as $index => $panen)
                @php
                    $totalLuas += $panen->luas_lahan_ha;
                    $totalPanen += $panen->jumlah_panen_ton;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $panen->satker->kode_satker ?? '-' }}</td>
                    <td>{{ $panen->satker->nama_satker ?? '-' }}</td>
                    <td class="text-center">{{ $panen->tanggal_panen ? date('d/m/Y', strtotime($panen->tanggal_panen)) : '-' }}</td>
                    <td class="text-center">
                        @if($panen->komoditas == 'padi')
                            <span class="badge badge-padi">PADI</span>
                        @else
                            <span class="badge badge-jagung">JAGUNG</span>
                        @endif
                    </td>
                    <td>{{ $panen->varietas->nama_varietas ?? '-' }}</td>
                    <td class="text-end">{{ number_format($panen->luas_lahan_ha, 2, ',', '.') }}</td>
                    <td class="text-end" style="font-weight: bold; color: #1b5e20;">{{ number_format($panen->jumlah_panen_ton, 2, ',', '.') }}</td>
                    <td>{{ $panen->lokasi_lahan ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center" style="padding: 15px; color: #777;">
                        Belum ada data catatan panen yang tersedia.
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if(count($catatanPanens) > 0)
        <tfoot>
            <tr class="table-footer">
                <td colspan="6" class="text-end" style="padding: 8px;">TOTAL REKAPITULASI:</td>
                <td class="text-end" style="color: #1b5e20;">{{ number_format($totalLuas, 2, ',', '.') }} Ha</td>
                <td class="text-end" style="color: #1b5e20;">{{ number_format($totalPanen, 2, ',', '.') }} Ton</td>
                <td></td>
            </tr>
        </tfoot>
        @endif
    </table>

    <!-- Footer Lembar Tanda Tangan / Informasi Dokumen -->
    <div class="footer">
        <table class="footer-table">
            <tr>
                <td style="width: 65%; font-size: 9px; color: #777; vertical-align: bottom;">
                    * Dokumen ini dibuat secara otomatis melalui Sistem Informasi Stok Benih & Panen BBPSIP.
                </td>
                <td style="width: 35%; text-align: center;">
                    <p style="margin-bottom: 50px;">
                        Mengetahui,<br>
                        <strong>Penanggung Jawab Satker</strong>
                    </p>
                    <p style="margin: 0;">( ___________________________ )</p>
                    <p style="margin-top: 2px; font-size: 9px; color: #555;">NIP. .................................................</p>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
