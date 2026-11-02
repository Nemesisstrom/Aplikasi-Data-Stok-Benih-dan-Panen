<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 1. Super Admin Bypass (Role admin_pusat diizinkan untuk semua aksi)
        Gate::before(function ($user, $ability) {
            return $user->hasRole('admin_pusat') ? true : null;
        });

        // 2. Daftarkan Gate khusus untuk 'export excel' dan 'export pdf'
        // Ini mencegah Laravel mencari Policy Class saat membaca @can('export excel') atau @can('export pdf')
        Gate::define('export excel', function ($user) {
            return $user->hasPermissionTo('export excel') || $user->hasAnyRole(['admin_pusat', 'petugas_provinsi', 'pimpinan']);
        });

        Gate::define('export pdf', function ($user) {
            return $user->hasPermissionTo('export pdf') || $user->hasAnyRole(['admin_pusat', 'petugas_provinsi', 'pimpinan']);
        });
    }
}
