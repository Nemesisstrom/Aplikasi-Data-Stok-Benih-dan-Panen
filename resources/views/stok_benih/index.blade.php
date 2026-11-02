@extends('layouts.app')

@section('title', 'Kelola Stok Benih - Admin Pusat')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold text-success mb-0"><i class="bi bi-box-seam-fill me-2"></i>Manajemen Stok Benih BPSIP</h4>
    <div class="d-flex gap-2">
        @can('export excel')
        <a href="{{ route('stok.export.excel', request()->query()) }}" class="btn btn-outline-success shadow-sm">
            <i class="bi bi-file-earmark-excel-fill me-1"></i> Ekspor Excel
        </a>
        @endcan
        @can('export pdf')
        <a href="{{ route('stok.export.pdf', request()->query()) }}" class="btn btn-outline-danger shadow-sm">
            <i class="bi bi-file-earmark-pdf-fill me-1"></i> Ekspor PDF
        </a>
        @endcan
        @hasanyrole('admin_pusat|petugas_provinsi')
        <button class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahStok">
            <i class="bi bi-plus-circle me-1"></i> Input Stok Benih
        </button>
        @endhasanyrole
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- Filter Box (Admin Pusat dapat memfilter per Satker) -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form action="{{ route('stok-benih.index') }}" method="GET" class="row g-3">
            @hasrole('admin_pusat')
            <div class="col-md-3">
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

            <div class="col-md-4">
                <select name="status_stok" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Semua Status Stok --</option>
                    <option value="tersedia_tersertifikasi" {{ request('status_stok') == 'tersedia_tersertifikasi' ? 'selected' : '' }}>Tersedia (Tersertifikasi)</option>
                    <option value="proses_sertifikasi" {{ request('status_stok') == 'proses_sertifikasi' ? 'selected' : '' }}>Proses Sertifikasi</option>
                    <option value="prosesing" {{ request('status_stok') == 'prosesing' ? 'selected' : '' }}>Prosesing</option>
                    <option value="persiapan_panen" {{ request('status_stok') == 'persiapan_panen' ? 'selected' : '' }}>Persiapan Panen</option>
                </select>
            </div>

            <div class="col-md-2">
                <a href="{{ route('stok-benih.index') }}" class="btn btn-outline-secondary w-100">Reset Filter</a>
            </div>
        </form>
    </div>
</div>

<!-- Table Data Stok -->
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">No</th>
                        <th>Satker BPSIP</th>
                        <th>Varietas & Komoditas</th>
                        <th>Kelas</th>
                        <th class="text-end">Volume (Ton)</th>
                        <th>Status Stok</th>
                        <th>No. Sertifikat</th>
                        <th class="text-end pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stokList as $index => $stok)
                    <tr>
                        <td class="ps-3">{{ $stokList->firstItem() + $index }}</td>
                        <td>
                            <strong class="d-block">{{ $stok->satker->nama_satker ?? '-' }}</strong>
                            <small class="text-muted">{{ $stok->satker->kode_satker ?? '' }}</small>
                        </td>
                        <td>
                            <span class="fw-bold">{{ $stok->varietas->nama_varietas ?? '-' }}</span>
                            <small class="d-block text-capitalize text-muted">{{ $stok->komoditas }}</small>
                        </td>
                        <td><span class="badge bg-info text-dark">{{ $stok->kelas_benih }}</span></td>
                        <td class="text-end fw-bold text-success">{{ number_format($stok->volume_ton, 2) }}</td>
                        <td>
                            @switch($stok->status_stok)
                                @case('tersedia_tersertifikasi')
                                    <span class="badge bg-success">Tersedia (Sertifikat)</span>
                                    @break
                                @case('proses_sertifikasi')
                                    <span class="badge bg-warning text-dark">Proses Sertifikasi</span>
                                    @break
                                @case('prosesing')
                                    <span class="badge bg-primary">Prosesing</span>
                                    @break
                                @default
                                    <span class="badge bg-secondary">Persiapan Panen</span>
                            @endswitch
                        </td>
                        <td><small>{{ $stok->nomor_sertifikat ?? '-' }}</small></td>
                        <td class="text-end pe-3">
                            @hasanyrole('admin_pusat|petugas_provinsi')
                            <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#modalEditStok{{ $stok->id }}">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <form action="{{ route('stok-benih.destroy', $stok->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data stok ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                            @else
                            <span class="text-muted small">Read Only</span>
                            @endhasanyrole
                        </td>
                    </tr>

                    <!-- Modal Edit Stok -->
                    <div class="modal fade" id="modalEditStok{{ $stok->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <form action="{{ route('stok-benih.update', $stok->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title fw-bold">Edit Data Stok Benih</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body row g-3">
                                        @hasrole('admin_pusat')
                                        <div class="col-md-12">
                                            <label class="form-label fw-semibold">Satuan Kerja BPSIP</label>
                                            <select name="satker_id" class="form-select" required>
                                                @foreach($satkers as $satker)
                                                    <option value="{{ $satker->id }}" {{ $stok->satker_id == $satker->id ? 'selected' : '' }}>{{ $satker->nama_satker }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @endhasrole

                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Komoditas</label>
                                            <select name="komoditas" class="form-select" required>
                                                <option value="padi" {{ $stok->komoditas == 'padi' ? 'selected' : '' }}>Padi</option>
                                                <option value="jagung" {{ $stok->komoditas == 'jagung' ? 'selected' : '' }}>Jagung</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Varietas</label>
                                            <select name="varietas_id" class="form-select" required>
                                                @foreach($varietas as $v)
                                                    <option value="{{ $v->id }}" {{ $stok->varietas_id == $v->id ? 'selected' : '' }}>{{ $v->nama_varietas }} ({{ ucfirst($v->komoditas) }})</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Kelas Benih</label>
                                            <select name="kelas_benih" class="form-select" required>
                                                <option value="BS" {{ $stok->kelas_benih == 'BS' ? 'selected' : '' }}>BS (Breeding Seed)</option>
                                                <option value="FS" {{ $stok->kelas_benih == 'FS' ? 'selected' : '' }}>FS (Foundation Seed)</option>
                                                <option value="SS" {{ $stok->kelas_benih == 'SS' ? 'selected' : '' }}>SS (Stock Seed)</option>
                                                <option value="ES" {{ $stok->kelas_benih == 'ES' ? 'selected' : '' }}>ES (Extension Seed)</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Volume (Ton)</label>
                                            <input type="number" step="0.01" name="volume_ton" class="form-control" value="{{ $stok->volume_ton }}" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Status Stok</label>
                                            <select name="status_stok" class="form-select" required>
                                                <option value="tersedia_tersertifikasi" {{ $stok->status_stok == 'tersedia_tersertifikasi' ? 'selected' : '' }}>Tersedia (Tersertifikasi)</option>
                                                <option value="proses_sertifikasi" {{ $stok->status_stok == 'proses_sertifikasi' ? 'selected' : '' }}>Proses Sertifikasi</option>
                                                <option value="prosesing" {{ $stok->status_stok == 'prosesing' ? 'selected' : '' }}>Prosesing</option>
                                                <option value="persiapan_panen" {{ $stok->status_stok == 'persiapan_panen' ? 'selected' : '' }}>Persiapan Panen</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Nomor Sertifikat</label>
                                            <input type="text" name="nomor_sertifikat" class="form-control" value="{{ $stok->nomor_sertifikat }}">
                                        </div>

                                        <div class="col-md-12">
                                            <label class="form-label fw-semibold">Catatan</label>
                                            <textarea name="catatan" class="form-control" rows="2">{{ $stok->catatan }}</textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">Belum ada data stok benih.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white border-0 py-3">
        {{ $stokList->links() }}
    </div>
</div>

<!-- Modal Tambah Stok Benih -->
<div class="modal fade" id="modalTambahStok" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('stok-benih.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Input Data Stok Benih Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row g-3">
                    @hasrole('admin_pusat')
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Satuan Kerja BPSIP</label>
                        <select name="satker_id" class="form-select" required>
                            <option value="">-- Pilih Satker BPSIP --</option>
                            @foreach($satkers as $satker)
                                <option value="{{ $satker->id }}">{{ $satker->nama_satker }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endhasrole

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Komoditas</label>
                        <select name="komoditas" id="komoditasTambah" class="form-select" required>
                            <option value="">-- Pilih Komoditas --</option>
                            <option value="padi">Padi</option>
                            <option value="jagung">Jagung</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Varietas</label>
                        <select name="varietas_id" id="varietasTambah" class="form-select" required>
                            <option value="">-- Pilih Komoditas Terlebih Dahulu --</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Kelas Benih</label>
                        <select name="kelas_benih" class="form-select" required>
                            <option value="BS">BS (Breeding Seed / Penjenis)</option>
                            <option value="FS">FS (Foundation Seed / Dasar)</option>
                            <option value="SS">SS (Stock Seed / Pokok)</option>
                            <option value="ES">ES (Extension Seed / Sebar)</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Volume (Ton)</label>
                        <input type="number" step="0.01" name="volume_ton" class="form-control" placeholder="0.00" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Status Stok</label>
                        <select name="status_stok" class="form-select" required>
                            <option value="tersedia_tersertifikasi">Tersedia (Tersertifikasi)</option>
                            <option value="proses_sertifikasi">Proses Sertifikasi</option>
                            <option value="prosesing">Prosesing / Pengolahan</option>
                            <option value="persiapan_panen">Persiapan Panen</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nomor Sertifikat (Opsional)</label>
                        <input type="text" name="nomor_sertifikat" class="form-control" placeholder="Contoh: 521/BS/2026">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Catatan Tambahan</label>
                        <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan kondisi benih..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan Data Stok</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const komoditasTambah = document.getElementById('komoditasTambah');
    const varietasTambah = document.getElementById('varietasTambah');

    if (komoditasTambah) {
        komoditasTambah.addEventListener('change', function () {
            const komoditas = this.value;
            varietasTambah.innerHTML = '<option value="">Memuat data varietas...</option>';

            if (komoditas) {
                fetch(`/api/varietas-by-komoditas/${komoditas}`)
                    .then(response => response.json())
                    .then(data => {
                        varietasTambah.innerHTML = '<option value="">-- Pilih Varietas --</option>';
                        data.forEach(item => {
                            varietasTambah.innerHTML += `<option value="${item.id}">${item.nama_varietas}</option>`;
                        });
                    })
                    .catch(() => {
                        varietasTambah.innerHTML = '<option value="">Gagal memuat varietas</option>';
                    });
            } else {
                varietasTambah.innerHTML = '<option value="">-- Pilih Komoditas Terlebih Dahulu --</option>';
            }
        });
    }
});
</script>
@endpush
