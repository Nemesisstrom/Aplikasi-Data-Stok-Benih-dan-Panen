@extends('layouts.app')

@section('title', 'Edit Catatan Hasil Panen')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold text-success mb-0">
        <i class="bi bi-pencil-square me-2"></i>Edit Catatan Hasil Panen
    </h4>
    <a href="{{ route('catatan-panen.index') }}" class="btn btn-outline-secondary shadow-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
    </a>
</div>

@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
    <strong class="d-block mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> Gagal Memperbarui Data!</strong>
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
        <form action="{{ route('catatan-panen.update', $catatanPanen->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                @hasrole('admin_pusat')
                <div class="col-md-12">
                    <label class="form-label fw-semibold">Satuan Kerja BPSIP <span class="text-danger">*</span></label>
                    <select name="satker_id" class="form-select @error('satker_id') is-invalid @enderror" required>
                        @foreach($satkers as $satker)
                            <option value="{{ $satker->id }}" {{ old('satker_id', $catatanPanen->satker_id) == $satker->id ? 'selected' : '' }}>
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
                        <option value="padi" {{ old('komoditas', $catatanPanen->komoditas) == 'padi' ? 'selected' : '' }}>Padi</option>
                        <option value="jagung" {{ old('komoditas', $catatanPanen->komoditas) == 'jagung' ? 'selected' : '' }}>Jagung</option>
                    </select>
                    @error('komoditas')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Varietas <span class="text-danger">*</span></label>
                    <select name="varietas_id" id="varietasSelect" class="form-select @error('varietas_id') is-invalid @enderror" required>
                        @foreach($varietas as $v)
                            <option value="{{ $v->id }}" {{ old('varietas_id', $catatanPanen->varietas_id) == $v->id ? 'selected' : '' }}>
                                {{ $v->nama_varietas }} ({{ ucfirst($v->komoditas) }})
                            </option>
                        @endforeach
                    </select>
                    @error('varietas_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Luas Lahan (Hektar) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="0.01" name="luas_lahan_ha" class="form-control @error('luas_lahan_ha') is-invalid @enderror" value="{{ old('luas_lahan_ha', $catatanPanen->luas_lahan_ha ?? $catatanPanen->luas_lahan_hektar) }}" required>
                    @error('luas_lahan_ha')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Hasil Panen (Ton) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="0.01" name="jumlah_panen_ton" class="form-control @error('jumlah_panen_ton') is-invalid @enderror" value="{{ old('jumlah_panen_ton', $catatanPanen->jumlah_panen_ton) }}" required>
                    @error('jumlah_panen_ton')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tanggal Panen <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_panen" class="form-control @error('tanggal_panen') is-invalid @enderror" value="{{ old('tanggal_panen', \Carbon\Carbon::parse($catatanPanen->tanggal_panen)->format('Y-m-d')) }}" required>
                    @error('tanggal_panen')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Lokasi Lahan / KP (Opsional)</label>
                    <input type="text" name="lokasi_lahan" class="form-control @error('lokasi_lahan') is-invalid @enderror" value="{{ old('lokasi_lahan', $catatanPanen->lokasi_lahan) }}" placeholder="Contoh: KP Muara / KP Sukamandi">
                    @error('lokasi_lahan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-semibold">Catatan Tambahan</label>
                    <textarea name="catatan" class="form-control @error('catatan') is-invalid @enderror" rows="3">{{ old('catatan', $catatanPanen->catatan) }}</textarea>
                    @error('catatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('catatan-panen.index') }}" class="btn btn-light border">Batal</a>
                <button type="submit" class="btn btn-success px-4">
                    <i class="bi bi-save me-1"></i> Simpan Perubahan
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

    komoditasSelect.addEventListener('change', function () {
        const komoditas = this.value;
        varietasSelect.innerHTML = '<option value="">Memuat data varietas...</option>';

        if (komoditas) {
            fetch(`/api/varietas-by-komoditas/${komoditas}`)
                .then(response => response.json())
                .then(data => {
                    varietasSelect.innerHTML = '<option value="">-- Pilih Varietas --</option>';
                    data.forEach(item => {
                        varietasSelect.innerHTML += `<option value="${item.id}">${item.nama_varietas}</option>`;
                    });
                })
                .catch(() => {
                    varietasSelect.innerHTML = '<option value="">Gagal memuat varietas</option>';
                });
        }
    });
});
</script>
@endpush
