<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Petugas BPSIP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background: linear-gradient(135deg, #1b5e20 0%, #2e7d32 100%); min-height: 100vh; display: flex; align-items: center; }
        .card-register { border: none; border-radius: 15px; }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card card-register shadow-lg p-4">
                    <div class="text-center mb-3">
                        <h4 class="fw-bold text-success">Pendaftaran Petugas Satker</h4>
                        <p class="text-muted small">Buat akun petugas daerah untuk mengelola stok benih & panen</p>
                    </div>

                    <form action="{{ route('register') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: Rahmat Hidayat" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Alamat Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="nama@pertanian.go.id" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Satuan Kerja (BPSIP)</label>
                            <select name="satker_id" class="form-select @error('satker_id') is-invalid @enderror" required>
                                <option value="">-- Pilih BPSIP Daerah --</option>
                                @foreach($satkers as $satker)
                                    <option value="{{ $satker->id }}" {{ old $satker->id ? 'selected' : '' }}>
                                        [{{ $satker->kode_satker }}] {{ $satker->nama_satker }}
                                    </option>
                                @endforeach
                            </select>
                            @error('satker_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Password</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Min. 8 Karakter" required>
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi Password" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success w-100 py-2 fw-bold mt-2">
                            <i class="bi bi-person-plus me-1"></i> Daftar Akun Petugas
                        </button>
                    </form>

                    <div class="text-center mt-3">
                        <small class="text-muted">Sudah punya akun? <a href="{{ route('login') }}" class="text-success fw-bold text-decoration-none">Login di sini</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
