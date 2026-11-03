<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Data Stok Benih & Panen - BBPSIP</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .sidebar { min-height: 100vh; background-color: #1b5e20; color: #fff; }
        .sidebar .nav-link { color: rgba(255,255,255,0.8); margin-bottom: 4px; border-radius: 6px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background-color: #2e7d32; color: #fff; }
        .top-navbar { background-color: #fff; border-bottom: 1px solid #e0e0e0; }
        .card-stat { border: none; border-radius: 10px; transition: transform 0.2s; }
        .card-stat:hover { transform: translateY(-3px); }
    </style>
    <!-- Script Global atau Stack untuk halaman spesifik -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse p-3">
                <div class="d-flex align-items-center mb-4 px-2">
                    <i class="bi bi-tree-fill fs-3 me-2 text-warning"></i>
                    <span class="fs-5 fw-bold">BBPSIP STOK</span>
                </div>
                <hr class="text-white-50">
                <ul class="nav nav-pills flex-column mb-auto">
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('stok-benih.index') }}" class="nav-link {{ request()->is('stok-benih*') ? 'active' : '' }}">
                            <i class="bi bi-box-seam me-2"></i> Stok Benih
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('catatan-panen.index') }}" class="nav-link {{ request()->is('catatan-panen*') ? 'active' : '' }}">
                            <i class="bi bi-journal-text me-2"></i> Catatan Panen
                        </a>
                    </li>
                    @role('admin_pusat')
                    <hr class="text-white-50 my-2">
                    <span class="text-white-50 px-2 small fw-bold text-uppercase">Pengaturan Sistem</span>
                    <li class="mt-1">
                        <a href="{{ route('users.index') }}" class="nav-link {{ request()->is('users*') ? 'active' : '' }}">
                            <i class="bi bi-people me-2"></i> Manajemen User
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('roles.index') }}" class="nav-link {{ request()->is('roles*') ? 'active' : '' }}">
                            <i class="bi bi-shield-lock me-2"></i> Role & Akses
                        </a>
                    </li>
                    @endrole
                </ul>
            </nav>

            <!-- Main Content Area -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <!-- Top Navbar -->
                <div class="top-navbar d-flex justify-content-between align-items-center py-3 px-3 mb-4 rounded-bottom shadow-sm">
                    <h5 class="mb-0 text-secondary fw-semibold">
                        @yield('title', 'Dashboard Monitoring')
                    </h5>
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle fs-4 me-2 text-success"></i>
                            <div>
                                <strong class="d-block leading-tight">{{ auth()->user()->name }}</strong>
                                <small class="text-muted">{{ strtoupper(auth()->user()->getRoleNames()->first() ?? 'Petugas') }}</small>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownUser">
                            <li><span class="dropdown-item-text text-muted">{{ auth()->user()->email }}</span></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i> Logout / Keluar
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Alert Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                        <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Page Dynamic Content -->
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
