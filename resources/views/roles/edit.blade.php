@extends('layouts.app')

@section('title', 'Edit Role & Hak Akses')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold text-success mb-0">
        <i class="bi bi-shield-check me-2"></i>Edit Role: <span class="text-uppercase text-dark">{{ $role->name }}</span>
    </h4>
    <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary shadow-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Role
    </a>
</div>

@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
    <strong class="d-block mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> Gagal Memperbarui Role!</strong>
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
        <form action="{{ route('roles.update', $role->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="form-label fw-semibold">Nama Role <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $role->name) }}" required
                       {{ in_array($role->name, ['admin_pusat', 'petugas_provinsi', 'pimpinan']) ? 'readonly' : '' }}>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                @if(in_array($role->name, ['admin_pusat', 'petugas_provinsi', 'pimpinan']))
                    <small class="text-warning"><i class="bi bi-lock-fill me-1"></i> Nama role bawaan sistem tidak dapat diubah.</small>
                @endif
            </div>

            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label class="form-label fw-semibold mb-0">Kelola Hak Akses (Permissions) <span class="text-danger">*</span></label>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="selectAll">Pilih Semua</button>
                </div>

                <div class="row g-2">
                    @forelse($permissions as $perm)
                    <div class="col-md-6 col-lg-4">
                        <div class="form-check border p-2 rounded bg-light hover-shadow">
                            <input class="form-check-input ms-1 perm-checkbox" type="checkbox" name="permissions[]"
                                   value="{{ $perm->id }}" id="perm_{{ $perm->id }}"
                                   {{ (is_array(old('permissions')) && in_array($perm->id, old('permissions'))) || (isset($rolePermissions) && in_array($perm->name, $rolePermissions)) || $role->hasPermissionTo($perm->name) ? 'checked' : '' }}>
                            <label class="form-check-label ms-2 fw-semibold text-dark" for="perm_{{ $perm->id }}">
                                {{ $perm->name }}
                            </label>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <p class="text-muted italic mb-0">Belum ada permission terdaftar di database.</p>
                    </div>
                    @endforelse
                </div>
                @error('permissions')
                    <div class="text-danger small mt-2">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('roles.index') }}" class="btn btn-light border">Batal</a>
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
    const selectAllBtn = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.perm-checkbox');

    if (selectAllBtn) {
        selectAllBtn.addEventListener('click', function () {
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            checkboxes.forEach(cb => cb.checked = !allChecked);
            this.textContent = allChecked ? 'Pilih Semua' : 'Batalkan Semua';
        });
    }
});
</script>
@endpush
