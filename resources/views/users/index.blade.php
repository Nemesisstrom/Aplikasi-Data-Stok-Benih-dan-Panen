@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold text-success mb-0"><i class="bi bi-people-fill me-2"></i>Manajemen Pengguna (Users)</h4>
    <button class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
        <i class="bi bi-person-plus-fill me-1"></i> Tambah User Baru
    </button>
</div>

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">No</th>
                        <th>Nama User</th>
                        <th>Email</th>
                        <th>Role Akses</th>
                        <th>Satker BPSIP</th>
                        <th class="text-end pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $u)
                    <tr>
                        <td class="ps-3">{{ $users->firstItem() + $index }}</td>
                        <td class="fw-bold">{{ $u->name }}</td>
                        <td>{{ $u->email }}</td>
                        <td>
                            @foreach($u->getRoleNames() as $roleName)
                                <span class="badge bg-success-subtle text-success border border-success">{{ strtoupper($roleName) }}</span>
                            @endforeach
                        </td>
                        <td>{{ $u->satker->nama_satker ?? 'Pusat / Lintas Satker' }}</td>
                        <td class="text-end pe-3">
                            <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#modalEditUser{{ $u->id }}">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            @if($u->id !== auth()->id())
                            <form action="{{ route('users.destroy', $u->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus user {{ $u->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                            @endif
                        </td>
                    </tr>

                    <!-- Modal Edit User -->
                    <div class="modal fade" id="modalEditUser{{ $u->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('users.update', $u->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title fw-bold">Edit Data User</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body row g-3">
                                        <div class="col-12">
                                            <label class="form-label fw-semibold">Nama Lengkap</label>
                                            <input type="text" name="name" class="form-control" value="{{ $u->name }}" required>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-semibold">Email</label>
                                            <input type="email" name="email" class="form-control" value="{{ $u->email }}" required>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-semibold">Password <small class="text-muted">(Kosongkan jika tidak diganti)</small></label>
                                            <input type="password" name="password" class="form-control" placeholder="Password Baru">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-semibold">Role Akses</label>
                                            <select name="role" class="form-select" required>
                                                @foreach($roles as $r)
                                                    <option value="{{ $r->name }}" {{ $u->hasRole($r->name) ? 'selected' : '' }}>{{ strtoupper($r->name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-semibold">Satker BPSIP</label>
                                            <select name="satker_id" class="form-select">
                                                <option value="">-- Pusat / Tanpa Satker --</option>
                                                @foreach($satkers as $s)
                                                    <option value="{{ $s->id }}" {{ $u->satker_id == $s->id ? 'selected' : '' }}>{{ $s->nama_satker }}</option>
                                                @endforeach
                                            </select>
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
                        <td colspan="6" class="text-center text-muted py-4">Belum ada pengguna terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white border-0 py-3">
        {{ $users->links() }}
    </div>
</div>

<!-- Modal Tambah User -->
<div class="modal fade" id="modalTambahUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tambah User Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" required placeholder="Nama User">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Alamat Email</label>
                        <input type="email" name="email" class="form-control" required placeholder="email@pertanian.go.id">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Password</label>
                        <input type="password" name="password" class="form-control" required placeholder="Min. 8 Karakter">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Role Akses</label>
                        <select name="role" class="form-select" required>
                            <option value="">-- Pilih Role --</option>
                            @foreach($roles as $r)
                                <option value="{{ $r->name }}">{{ strtoupper($r->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Satker BPSIP</label>
                        <select name="satker_id" class="form-select">
                            <option value="">-- Pusat / Tanpa Satker --</option>
                            @foreach($satkers as $s)
                                <option value="{{ $s->id }}">{{ $s->nama_satker }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan User</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
