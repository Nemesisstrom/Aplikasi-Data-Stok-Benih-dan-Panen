@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Dashboard BBPSIP</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Ringkasan Stok Benih & Catatan Panen Nasional</li>
    </ol>

    <!-- Kartu Statistik -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">Padi Tersedia (Sertifikasi)</div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span class="fs-4 fw-bold">{{ number_format($padiTersedia, 2, ',', '.') }} Ton</span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">Padi Dalam Proses</div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span class="fs-4 fw-bold">{{ number_format($padiProses, 2, ',', '.') }} Ton</span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-dark mb-4">
                <div class="card-body">Jagung Tersedia (Sertifikasi)</div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span class="fs-4 fw-bold">{{ number_format($jagungTersedia, 2, ',', '.') }} Ton</span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white mb-4">
                <div class="card-body">Jagung Dalam Proses</div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span class="fs-4 fw-bold">{{ number_format($jagungProses, 2, ',', '.') }} Ton</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Visualisasi Chart.js dengan Data Attributes -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-area me-1"></i>
                    Grafik Total Panen Padi Bulanan Tahun {{ date('Y') }}
                </div>
                <div class="card-body">
                    <canvas id="panenChart"
                            data-labels='{!! json_encode($labelsBulan) !!}'
                            data-values='{!! json_encode($dataPanen) !!}'
                            width="100%" height="30"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- CDN Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const canvasElement = document.getElementById('panenChart');
        if (!canvasElement) return;

        // Ambil data dari atribut HTML, sehingga bersih dari error linter editor
        const labelsBulan = JSON.parse(canvasElement.getAttribute('data-labels'));
        const dataPanen = JSON.parse(canvasElement.getAttribute('data-values'));

        const ctx = canvasElement.getContext('2d');
        window.panenChartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labelsBulan,
                datasets: [{
                    label: 'Total Panen (Ton)',
                    data: dataPanen,
                    backgroundColor: 'rgba(25, 135, 84, 0.2)',
                    borderColor: 'rgba(25, 135, 84, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection
