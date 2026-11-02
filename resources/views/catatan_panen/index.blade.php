@extends('layouts.app')

@section('title', 'Catatan Hasil Panen - BBPSIP')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold text-success mb-0">
        <i class="bi bi-journal-text me-2"></i>Catatan Realisasi Hasil Panen BPSIP
    </h4>
    <div class="d-flex gap-2">
        @can('export excel')
        <a href="{{ route('panen.export.excel', request()->query()) }}" class="btn btn-outline-success shadow-sm">
            <i class="bi bi-file-earmark-excel-fill me-1"></i> Ekspor Excel
        </a>
        @endcan
        @can('export pdf')
        <a href="{{ route('panen.export.pdf', request()->query()) }}" class="btn btn-outline-danger shadow-sm">
            <i class="bi bi-file-earmark-pdf-fill me-1"></i> Ekspor PDF
        </a>
        @endcan
        @hasanyrole('admin_pusat|petugas_provinsi')
        <a href="{{ route('catatan-panen.create') }}" class="btn btn-success shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> Input Hasil Panen
        </a>
        @endhasanyrole
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- Filter Data -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form action="{{ route('catatan-panen.index') }}" method="GET" class="row g-3">
            @hasrole('admin_pusat')
            <div class="col-md-4">
                <select name="satker_id" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Semua Satker BPSIP --</option>
                    @foreach($satkers as $satker)
                        <option value="{{ $satker->id }}" {{ request('satker_id') == $satker->id ? 'selected' : '' }}>
                            {{ $satker->nama_satker }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endhasrole
            <div class="col-md-3">
                <select name="komoditas" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Semua Komoditas --</option>
                    <option value="padi" {{ request('komoditas') == 'padi' ? 'selected' : '' }}>Padi</option>
                    <option value="jagung" {{ request('komoditas') == 'jagung' ? 'selected' : '' }}>Jagung</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="number" name="tahun" class="form-control" placeholder="Tahun Panen (cth: 2026)" value="{{ request('tahun') }}" onchange="this.form.submit()">
            </div>
            <div class="col-md-2">
                <a href="{{ route('catatan-panen.index') }}" class="btn btn-outline-secondary w-100">Reset Filter</a>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Utama Catatan Panen -->
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3" style="width: 5%;">No</th>
                        <th style="width: 20%;">Satker BPSIP</th>
                        <th style="width: 20%;">Varietas & Komoditas</th>
                        <th class="text-end" style="width: 12%;">Luas Lahan (Ha)</th>
                        <th class="text-end" style="width: 12%;">Jumlah Panen (Ton)</th>
                        <th style="width: 13%;">Tanggal Panen</th>
                        <th style="width: 10%;">Petugas Input</th>
                        <th class="text-end pe-3" style="width: 8%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($panenList as $index => $panen)
                    <tr>
                        <td class="ps-3">{{ $panenList->firstItem() + $index }}</td>
                        <td>
                            <strong class="d-block text-dark">{{ $panen->satker->nama_satker ?? '-' }}</strong>
                            <small class="text-muted">{{ $panen->satker->kode_satker ?? '' }}</small>
                        </td>
                        <td>
                            <span class="fw-bold">{{ $panen->varietas->nama_varietas ?? '-' }}</span>
                            <small class="d-block text-capitalize text-muted">{{ $panen->komoditas }}</small>
                        </td>
                        <td class="text-end fw-semibold">
                            {{ number_format($panen->luas_lahan_ha ?? $panen->luas_lahan_hektar ?? 0, 2, ',', '.') }} Ha
                        </td>
                        <td class="text-end fw-bold text-success">
                            {{ number_format($panen->jumlah_panen_ton, 2, ',', '.') }} Ton
                        </td>
                        <td>
                            <span>{{ \Carbon\Carbon::parse($panen->tanggal_panen)->translatedFormat('d M Y') }}</span>
                            @if($panen->lokasi_lahan)
                                <small class="d-block text-muted"><i class="bi bi-geo-alt"></i> {{ $panen->lokasi_lahan }}</small>
                            @endif
                        </td>
                        <td>
                            <small class="text-muted">{{ $panen->user->name ?? '-' }}</small>
                        </td>
                        <td class="text-end pe-3">
                            @hasanyrole('admin_pusat|petugas_provinsi')
                            <a href="{{ route('catatan-panen.edit', $panen->id) }}" class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('catatan-panen.destroy', $panen->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan panen ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                            </form>
                            @else
                            <span class="badge bg-light text-secondary">Read Only</span>
                            @endhasanyrole
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">Belum ada catatan hasil panen.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white border-0 py-3">
        {{ $panenList->links() }}
    </div>
</div>
@endsection
