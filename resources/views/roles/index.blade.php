@extends('layouts.app')

@section('title', 'Manajemen Role & Hak Akses')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold text-success mb-0"><i class="bi bi-shield-lock-fill me-2"></i>Role & Permission Management</h4>
    <button class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahRole">
        <i class="bi bi-plus-circle me-1"></i> Tambah Role Baru
    </button>
</div>

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row g-4">
    @foreach($roles as $role)
    <div class="col-md-6 col-lg-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-success text-uppercase">{{ $role->name }}</h6>
                @if(!in_array($role->name, ['admin_pusat', 'petugas_provinsi', 'pimpinan']))
                <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus role ini?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger border-0"><i class="bi bi-trash"></i></button>
                </form>
                @endif
            </div>
            <div class="card-body">
                <p class="text-muted small fw-bold mb-2">HAK AKSES / PERMISSIONS:</p>
                <div class="d-flex flex-wrap gap-1 mb-3">
                    @forelse($role->permissions as $p)
                        <span class="badge bg-secondary-subtle text-dark border">{{ $p->name }}</span>
                    @empty
                        <span class="text-muted small italic">Belum ada permission.</span>
                    @endforelse
                </div>
            </div>
            <div class="card-footer bg-white border-0 text-end pb-3">
                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEditRole{{ $role->id }}">
                    <i class="bi bi-pencil-square me-1"></i> Kelola Hak Akses
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Edit Role & Permission -->
    <div class="modal fade" id="modalEditRole{{ $role->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('roles.update', $role->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Kelola Hak Akses Role: {{ strtoupper($role->name) }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Role</label>
                            <input type="text" name="name" class="form-control" value="{{ $role->name }}" required>
                        </div>
                        <label class="form-label fw-semibold d-block">Pilih Hak Akses (Permissions):</label>
                        <div class="row g-2">
                            @foreach($permissions as $perm)
                            <div class="col-md-6">
                                <div class="form-check border p-2 rounded">
                                    <input class="form-check-input ms-1" type="checkbox" name="permissions[]" value="{{ $perm->id }}" id="perm_{{ $role->id }}_{{ $perm->id }}" {{ $role->hasPermissionTo($perm->name) ? 'checked' : '' }}>
                                    <label class="form-check-label ms-2" for="perm_{{ $role->id }}_{{ $perm->id }}">
                                        {{ $perm->name }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
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
    @endforeach
</div>

<!-- Modal Tambah Role -->
<div class="modal fade" id="modalTambahRole" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('roles.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Buat Role Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Role</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: verifikator_pusat" required>
                    </div>
                    <label class="form-label fw-semibold d-block">Pilih Hak Akses (Permissions):</label>
                    <div class="row g-2">
                        @foreach($permissions as $perm)
                        <div class="col-md-6">
                            <div class="form-check border p-2 rounded">
                                <input class="form-check-input ms-1" type="checkbox" name="permissions[]" value="{{ $perm->id }}" id="new_perm_{{ $perm->id }}">
                                <label class="form-check-label ms-2" for="new_perm_{{ $perm->id }}">
                                    {{ $perm->name }}
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan Role</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
