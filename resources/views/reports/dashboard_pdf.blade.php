<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ringkasan Dashboard BBPSIP</title>
    <style>
        body { font-family: sans-serif; color: #333; margin: 20px; }
        .header { text-align: center; border-bottom: 2px solid #2e7d32; padding-bottom: 10px; margin-bottom: 20px; }
        .header h2 { margin: 0; color: #1b5e20; }
        .header p { margin: 4px 0 0 0; font-size: 13px; color: #666; }
        .stat-grid { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        .stat-card { border: 1px solid #e0e0e0; padding: 12px; background: #f9f9f9; text-align: center; }
        .stat-card h4 { margin: 0; font-size: 18px; color: #2e7d32; }
        .stat-card span { font-size: 11px; color: #666; text-transform: uppercase; }
        .chart-container { text-align: center; margin-bottom: 20px; page-break-inside: avoid; }
        .chart-container img { width: 95%; max-height: 280px; border: 1px solid #ddd; padding: 5px; border-radius: 6px; }
        .footer { text-align: right; font-size: 10px; color: #888; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>BALAI BESAR PENERAPAN STANDAR INSTRUMEN PERTANIAN (BBPSIP)</h2>
        <p>Laporan Ringkasan Monitoring Stok Benih & Realisasi Panen</p>
        <p><small>Dicetak pada: {{ date('d F Y H:i') }} WIB oleh {{ auth()->user()->name }}</small></p>
    </div>

    <!-- Statistik Ringkas -->
    <table class="stat-grid">
        <tr>
            <td class="stat-card">
                <span>Stok Padi Tersedia</span>
                <h4>{{ number_format($padiTersedia, 2) }} Ton</h4>
            </td>
            <td class="stat-card">
                <span>Stok Jagung Tersedia</span>
                <h4>{{ number_format($jagungTersedia, 2) }} Ton</h4>
            </td>
            <td class="stat-card">
                <span>Realisasi Panen Padi</span>
                <h4>{{ number_format($totalPanenPadi, 2) }} Ton</h4>
            </td>
            <td class="stat-card">
                <span>Realisasi Panen Jagung</span>
                <h4>{{ number_format($totalPanenJagung, 2) }} Ton</h4>
            </td>
        </tr>
    </table>

    <!-- Render Gambar Grafik Base64 -->
    @if($lineChartBase64)
    <div class="chart-container">
        <h4 style="margin-bottom: 8px; color: #333;">Tren Hasil Panen Padi Bulanan</h4>
        <img src="{{ $lineChartBase64 }}" alt="Grafik Line Chart Tren Panen">
    </div>
    @endif

    @if($donutChartBase64)
    <div class="chart-container">
        <h4 style="margin-bottom: 8px; color: #333;">Distribusi Persentase Kelas Benih</h4>
        <img src="{{ $donutChartBase64 }}" alt="Grafik Donut Chart Kelas Benih">
    </div>
    @endif

    <div class="footer">
        Dokumen ini dihasilkan secara otomatis oleh Sistem Data Stok Benih & Panen BBPSIP.
    </div>
</body>
</html>
