@extends('layouts.app')

@section('title', 'Manajemen Master Varietas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold text-success mb-0"><i class="bi bi-tag-fill me-2"></i>Master Varietas Benih</h4>
    <button class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahVarietas">
        <i class="bi bi-plus-circle me-1"></i> Tambah Varietas
    </button>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">No</th>
                        <th>Nama Varietas</th>
                        <th>Komoditas</th>
                        <th>Deskripsi</th>
                        <th class="text-end pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($varietas as $index => $item)
                    <tr>
                        <td class="ps-3">{{ $varietas->firstItem() + $index }}</td>
                        <td class="fw-bold">{{ $item->nama_varietas }}</td>
                        <td>
                            @if($item->komoditas == 'padi')
                                <span class="badge bg-success-subtle text-success border border-success">Padi</span>
                            @else
                                <span class="badge bg-warning-subtle text-warning border border-warning">Jagung</span>
                            @endif
                        </td>
                        <td>{{ $item->deskripsi ?? '-' }}</td>
                        <td class="text-end pe-3">
                            <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#modalEditVarietas{{ $item->id }}">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <form action="{{ route('varietas.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus varietas ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>

                    <!-- Modal Edit Varietas -->
                    <div class="modal fade" id="modalEditVarietas{{ $item->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('varietas.update', $item->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title fw-bold">Edit Varietas</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Nama Varietas</label>
                                            <input type="text" name="nama_varietas" class="form-control" value="{{ $item->nama_varietas }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Komoditas</label>
                                            <select name="komoditas" class="form-select" required>
                                                <option value="padi" {{ $item->komoditas == 'padi' ? 'selected' : '' }}>Padi</option>
                                                <option value="jagung" {{ $item->komoditas == 'jagung' ? 'selected' : '' }}>Jagung</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Deskripsi</label>
                                            <textarea name="deskripsi" class="form-control" rows="3">{{ $item->deskripsi }}</textarea>
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
                        <td colspan="5" class="text-center text-muted py-4">Belum ada data varietas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white border-0 py-3">
        {{ $varietas->links() }}
    </div>
</div>

<!-- Modal Tambah Varietas -->
<div class="modal fade" id="modalTambahVarietas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('varietas.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tambah Varietas Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Varietas</label>
                        <input type="text" name="nama_varietas" class="form-control" placeholder="Contoh: Inpari 32 / HJ 21" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Komoditas</label>
                        <select name="komoditas" class="form-select" required>
                            <option value="">-- Pilih Komoditas --</option>
                            <option value="padi">Padi</option>
                            <option value="jagung">Jagung</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3" placeholder="Keterangan keunggulan varietas..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
