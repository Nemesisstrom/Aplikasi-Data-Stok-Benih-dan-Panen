@extends('layouts.app')

@section('title', 'Tambah Data Stok Benih')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold text-success mb-0">
        <i class="bi bi-box-seam-fill me-2"></i>Tambah Data Stok Benih Baru
    </h4>
    <a href="{{ route('stok-benih.index') }}" class="btn btn-outline-secondary shadow-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
    </a>
</div>

@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
    <strong class="d-block mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> Gagal Menyimpan Data!</strong>
    <ul class="mb-0 ps-3">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <form action="{{ route('stok-benih.store') }}" method="POST">
            @csrf

            <div class="row g-3">
                @hasrole('admin_pusat')
                <div class="col-md-12">
                    <label class="form-label fw-semibold">Satuan Kerja BPSIP <span class="text-danger">*</span></label>
                    <select name="satker_id" class="form-select @error('satker_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Satker BPSIP --</option>
                        @foreach($satkers as $satker)
                            <option value="{{ $satker->id }}" {{ old('satker_id') == $satker->id ? 'selected' : '' }}>
                                {{ $satker->nama_satker }}
                            </option>
                        @endforeach
                    </select>
                    @error('satker_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                @endhasrole

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Komoditas <span class="text-danger">*</span></label>
                    <select name="komoditas" id="komoditasSelect" class="form-select @error('komoditas') is-invalid @enderror" required>
                        <option value="">-- Pilih Komoditas --</option>
                        <option value="padi" {{ old('komoditas') == 'padi' ? 'selected' : '' }}>Padi</option>
                        <option value="jagung" {{ old('komoditas') == 'jagung' ? 'selected' : '' }}>Jagung</option>
                    </select>
                    @error('komoditas')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Varietas <span class="text-danger">*</span></label>
                    <select name="varietas_id" id="varietasSelect" class="form-select @error('varietas_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Komoditas Terlebih Dahulu --</option>
                    </select>
                    @error('varietas_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Kelas Benih <span class="text-danger">*</span></label>
                    <select name="kelas_benih" class="form-select @error('kelas_benih') is-invalid @enderror" required>
                        <option value="">-- Pilih Kelas Benih --</option>
                        <option value="BS" {{ old('kelas_benih') == 'BS' ? 'selected' : '' }}>BS (Breeding Seed / Penjenis)</option>
                        <option value="FS" {{ old('kelas_benih') == 'FS' ? 'selected' : '' }}>FS (Foundation Seed / Dasar)</option>
                        <option value="SS" {{ old('kelas_benih') == 'SS' ? 'selected' : '' }}>SS (Stock Seed / Pokok)</option>
                        <option value="ES" {{ old('kelas_benih') == 'ES' ? 'selected' : '' }}>ES (Extension Seed / Sebar)</option>
                    </select>
                    @error('kelas_benih')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Volume (Ton) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="0.01" name="volume_ton" class="form-control @error('volume_ton') is-invalid @enderror" value="{{ old('volume_ton') }}" placeholder="0.00" required>
                    @error('volume_ton')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Status Stok <span class="text-danger">*</span></label>
                    <select name="status_stok" class="form-select @error('status_stok') is-invalid @enderror" required>
                        <option value="tersedia_tersertifikasi" {{ old('status_stok') == 'tersedia_tersertifikasi' ? 'selected' : '' }}>Tersedia (Tersertifikasi)</option>
                        <option value="proses_sertifikasi" {{ old('status_stok') == 'proses_sertifikasi' ? 'selected' : '' }}>Proses Sertifikasi</option>
                        <option value="prosesing" {{ old('status_stok') == 'prosesing' ? 'selected' : '' }}>Prosesing / Pengolahan</option>
                        <option value="persiapan_panen" {{ old('status_stok') == 'persiapan_panen' ? 'selected' : '' }}>Persiapan Panen</option>
                    </select>
                    @error('status_stok')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nomor Sertifikat (Opsional)</label>
                    <input type="text" name="nomor_sertifikat" class="form-control @error('nomor_sertifikat') is-invalid @enderror" value="{{ old('nomor_sertifikat') }}" placeholder="Contoh: 521/BS/2026">
                    @error('nomor_sertifikat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-semibold">Catatan Tambahan</label>
                    <textarea name="catatan" class="form-control @error('catatan') is-invalid @enderror" rows="3" placeholder="Keterangan tambahan kondisi stok benih...">{{ old('catatan') }}</textarea>
                    @error('catatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('stok-benih.index') }}" class="btn btn-light border">Batal</a>
                <button type="submit" class="btn btn-success px-4">
                    <i class="bi bi-save me-1"></i> Simpan Data Stok
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const komoditasSelect = document.getElementById('komoditasSelect');
    const varietasSelect = document.getElementById('varietasSelect');
    const oldVarietasId = "{{ old('varietas_id') }}";

    function loadVarietas(komoditas, selectedId = null) {
        varietasSelect.innerHTML = '<option value="">Memuat data varietas...</option>';
        fetch(`/api/varietas-by-komoditas/${komoditas}`)
            .then(response => response.json())
            .then(data => {
                varietasSelect.innerHTML = '<option value="">-- Pilih Varietas --</option>';
                data.forEach(item => {
                    const isSelected = selectedId == item.id ? 'selected' : '';
                    varietasSelect.innerHTML += `<option value="${item.id}" ${isSelected}>${item.nama_varietas}</option>`;
                });
            })
            .catch(() => {
                varietasSelect.innerHTML = '<option value="">Gagal memuat data varietas</option>';
            });
    }

    if (komoditasSelect.value) {
        loadVarietas(komoditasSelect.value, oldVarietasId);
    }

    komoditasSelect.addEventListener('change', function () {
        if (this.value) {
            loadVarietas(this.value);
        } else {
            varietasSelect.innerHTML = '<option value="">-- Pilih Komoditas Terlebih Dahulu --</option>';
        }
    });
});
</script>
@endpush
