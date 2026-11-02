auth/login<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Stok Benih BBPSIP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background: linear-gradient(135deg, #1b5e20 0%, #2e7d32 100%); min-height: 100vh; display: flex; align-items: center; }
        .card-login { border: none; border-radius: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card card-login shadow-lg p-4">
                    <div class="text-center mb-4">
                        <i class="bi bi-tree-fill text-success fs-1"></i>
                        <h4 class="fw-bold text-success mt-2">Masuk ke Sistem</h4>
                        <p class="text-muted small">Stok Benih & Hasil Panen Padi/Jagung BBPSIP</p>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger small py-2 mb-3">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Alamat Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control" placeholder="nama@pertanian.go.id" value="{{ old('email') }}" required autofocus>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success w-100 py-2 fw-bold">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Masuk Sekarang
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        <small class="text-muted">Petugas BPSIP baru? <a href="{{ route('register') }}" class="text-success fw-bold text-decoration-none">Daftar Akun</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
