@extends('layouts.app')

@section('title', 'Dashboard Monitoring Stok & Panen')

@section('content')

<!-- Form Hidden untuk Mengirimkan Data Gambar Base64 Chart.js ke Backend Controller -->
<form id="exportPdfForm" action="{{ route('dashboard.export-pdf') }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="line_chart_img" id="lineChartInput">
    <input type="hidden" name="donut_chart_img" id="donutChartInput">
</form>

<!-- Banner Selamat Datang & Tombol Cetak PDF -->
<div class="row mb-4">
    <div class="col-12">
        <div class="p-4 bg-white rounded-3 shadow-sm border-start border-4 border-success d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="fw-bold text-success mb-1">Selamat Datang, {{ auth()->user()->name }}!</h4>
                <p class="text-muted mb-0">
                    @if(auth()->user()->satker)
                        Unit Satuan Kerja: <strong>{{ auth()->user()->satker->nama_satker }}</strong> ({{ auth()->user()->satker->provinsi->nama_provinsi ?? 'Nasional' }})
                    @else
                        Akses Administrator Pusat - Pengawasan Rekapitulasi Nasional BBPSIP.
                    @endif
                </p>
            </div>
            <div>
                <button type="button" id="btnCetakPdf" class="btn btn-danger shadow-sm">
                    <i class="bi bi-file-earmark-pdf-fill me-1"></i> Cetak PDF Dashboard
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Kartu Statistik Utama -->
<div class="row g-3 mb-4">
    <!-- Padi Tersedia -->
    <div class="col-md-6 col-lg-3">
        <div class="card card-stat shadow-sm bg-white p-3 border-start border-4 border-primary h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted small fw-bold text-uppercase mb-1">Stok Padi Tersedia</h6>
                    <h3 class="fw-bold text-primary mb-0">{{ number_format($padiTersedia, 2) }} <small class="fs-6 text-muted">Ton</small></h3>
                    <small class="text-muted">Prosesing: {{ number_format($padiProses, 2) }} Ton</small>
                </div>
                <div class="bg-primary-subtle p-3 rounded-circle text-primary">
                    <i class="bi bi-box-seam fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Jagung Tersedia -->
    <div class="col-md-6 col-lg-3">
        <div class="card card-stat shadow-sm bg-white p-3 border-start border-4 border-warning h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted small fw-bold text-uppercase mb-1">Stok Jagung Tersedia</h6>
                    <h3 class="fw-bold text-warning mb-0">{{ number_format($jagungTersedia, 2) }} <small class="fs-6 text-muted">Ton</small></h3>
                    <small class="text-muted">Prosesing: {{ number_format($jagungProses, 2) }} Ton</small>
                </div>
                <div class="bg-warning-subtle p-3 rounded-circle text-warning">
                    <i class="bi bi-flower1 fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Panen Padi -->
    <div class="col-md-6 col-lg-3">
        <div class="card card-stat shadow-sm bg-white p-3 border-start border-4 border-success h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted small fw-bold text-uppercase mb-1">Panen Padi (Realisasi)</h6>
                    <h3 class="fw-bold text-success mb-0">{{ number_format($totalPanenPadi, 2) }} <small class="fs-6 text-muted">Ton</small></h3>
                    <small class="text-muted">Potensi: {{ number_format($padiPotensiPanen, 2) }} Ton</small>
                </div>
                <div class="bg-success-subtle p-3 rounded-circle text-success">
                    <i class="bi bi-graph-up-arrow fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Panen Jagung -->
    <div class="col-md-6 col-lg-3">
        <div class="card card-stat shadow-sm bg-white p-3 border-start border-4 border-info h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted small fw-bold text-uppercase mb-1">Panen Jagung (Realisasi)</h6>
                    <h3 class="fw-bold text-info mb-0">{{ number_format($totalPanenJagung, 2) }} <small class="fs-6 text-muted">Ton</small></h3>
                    <small class="text-muted">Potensi: {{ number_format($jagungPotensiPanen, 2) }} Ton</small>
                </div>
                <div class="bg-info-subtle p-3 rounded-circle text-info">
                    <i class="bi bi-pie-chart fs-3"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Section Visual Analytics: Layout 2 Kolom Chart.js -->
<div class="row g-4 mb-4">
    <!-- Kolom Kiri: Line Chart Tren Panen Padi Bulanan -->
    <div class="col-lg-7">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-success">
                    <i class="bi bi-graph-up-arrow me-2"></i>Tren Panen Padi Bulanan (Tahun {{ date('Y') }})
                </h6>
                <span class="badge bg-success-subtle text-success">Realisasi (Ton)</span>
            </div>
            <div class="card-body">
                <div style="position: relative; height: 300px; width: 100%;">
                    <canvas id="trenPanenLineChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Donut Chart Distribusi Kelas Benih -->
    <div class="col-lg-5">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-success">
                    <i class="bi bi-pie-chart-fill me-2"></i>Distribusi Kelas Benih
                </h6>
                <span class="badge bg-info-subtle text-info">BS / FS / SS / ES</span>
            </div>
            <div class="card-body">
                <div style="position: relative; height: 300px; width: 100%;">
                    <canvas id="kelasBenihDonutChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Rekap BPSIP (Khusus Admin & Pimpinan) -->
@if(auth()->user()->hasAnyRole(['admin_pusat', 'pimpinan']) && $rekapSatker)
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0 text-success"><i class="bi bi-table me-2"></i>Rekapitulasi Stok Benih per Satker BPSIP</h6>
        <a href="{{ route('stok.export.excel') }}" class="btn btn-sm btn-outline-success">
            <i class="bi bi-file-earmark-excel me-1"></i> Ekspor Excel
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Kode Satker</th>
                    <th>Nama BPSIP</th>
                    <th class="text-end">Stok Padi (Ton)</th>
                    <th class="text-end">Stok Jagung (Ton)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rekapSatker as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><span class="badge bg-secondary">{{ $item->kode_satker }}</span></td>
                    <td class="fw-semibold">{{ $item->nama_satker }}</td>
                    <td class="text-end text-primary fw-bold">{{ number_format($item->stok_padi_tersedia ?? 0, 2) }}</td>
                    <td class="text-end text-warning fw-bold">{{ number_format($item->stok_jagung_tersedia ?? 0, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-3">Belum ada data rekapitulasi Satker.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // -------------------------------------------------------------
    // Handler Tombol Cetak PDF Dashboard
    // -------------------------------------------------------------
    const btnCetakPdf = document.getElementById('btnCetakPdf');
    if (btnCetakPdf) {
        btnCetakPdf.addEventListener('click', function () {
            const btn = this;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menyiapkan PDF...';

            // Ambil elemen Canvas Grafik
            const lineCanvas = document.getElementById('trenPanenLineChart');
            const donutCanvas = document.getElementById('kelasBenihDonutChart');

            // Konversi Canvas ke String Base64 PNG
            const lineBase64 = lineCanvas ? lineCanvas.toDataURL('image/png') : '';
            const donutBase64 = donutCanvas ? donutCanvas.toDataURL('image/png') : '';

            // Masukkan ke Input Hidden Form
            document.getElementById('lineChartInput').value = lineBase64;
            document.getElementById('donutChartInput').value = donutBase64;

            // Submit Form POST
            document.getElementById('exportPdfForm').submit();

            // Reset Status Tombol
            setTimeout(() => {
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-file-earmark-pdf-fill me-1"></i> Cetak PDF Dashboard';
            }, 3000);
        });
    }

    // -------------------------------------------------------------
    // 1. Line Chart Tren Panen Padi
    // -------------------------------------------------------------
    const ctxLine = document.getElementById('trenPanenLineChart').getContext('2d');
    const labelsBulan = @json($labelsBulan);
    const dataPanen = @json($dataPanen);

    const gradientFill = ctxLine.createLinearGradient(0, 0, 0, 300);
    gradientFill.addColorStop(0, 'rgba(46, 125, 50, 0.35)');
    gradientFill.addColorStop(1, 'rgba(46, 125, 50, 0.0)');

    new window.Chart(ctxLine, {
        type: 'line',
        data: {
            labels: labelsBulan,
            datasets: [{
                label: 'Panen Padi (Ton)',
                data: dataPanen,
                borderColor: '#2e7d32',
                backgroundColor: gradientFill,
                borderWidth: 3,
                fill: true,
                tension: 0.35,
                pointRadius: 4,
                pointBackgroundColor: '#1b5e20'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return ' Realisasi: ' + new window.Intl.NumberFormat('id-ID', { minimumFractionDigits: 2 }).format(context.parsed.y) + ' Ton';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return new window.Intl.NumberFormat('id-ID').format(value) + ' T';
                        }
                    }
                },
                x: { grid: { display: false } }
            }
        }
    });

    // -------------------------------------------------------------
    // 2. Donut Chart Kelas Benih
    // -------------------------------------------------------------
    const ctxDonut = document.getElementById('kelasBenihDonutChart').getContext('2d');
    const dataKelas = @json($dataKelas);
    const totalStok = Object.values(dataKelas).reduce((a, b) => a + b, 0);

    new window.Chart(ctxDonut, {
        type: 'doughnut',
        data: {
            labels: ['BS (Breeding)', 'FS (Foundation)', 'SS (Stock)', 'ES (Extension)'],
            datasets: [{
                data: [
                    dataKelas.BS || 0,
                    dataKelas.FS || 0,
                    dataKelas.SS || 0,
                    dataKelas.ES || 0
                ],
                backgroundColor: ['#198754', '#0d6efd', '#ffc107', '#0dcaf0'],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { boxWidth: 10, padding: 10, font: { size: 11, weight: '600' } }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let value = context.parsed || 0;
                            let percentage = totalStok > 0 ? ((value / totalStok) * 100).toFixed(1) : 0;
                            let formattedValue = new window.Intl.NumberFormat('id-ID', { minimumFractionDigits: 2 }).format(value);
                            return ` ${context.label}: ${formattedValue} Ton (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
