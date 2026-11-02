<?php

namespace App\Http\Controllers;

use App\Models\StokBenih;
use App\Models\CatatanPanen;
use App\Exports\StokBenihExport;
use App\Exports\CatatanPanenExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class LaporanController extends Controller implements HasMiddleware
{
    /**
     * Registrasi Middleware untuk Laravel 11/13
     */
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('permission:export excel|export pdf'),
        ];
    }

    public function exportStokExcel(Request $request)
    {
        $user = auth()->user();
        $satkerId = $user->hasRole('petugas_provinsi') ? $user->satker_id : $request->query('satker_id');
        $komoditas = $request->query('komoditas');

        $fileName = 'Laporan_Stok_Benih_BBPSIP_' . date('Ymd_His') . '.xlsx';

        return Excel::download(new StokBenihExport($satkerId, $komoditas), $fileName);
    }

    public function exportStokPdf(Request $request)
    {
        $user = auth()->user();
        $query = StokBenih::with(['satker', 'varietas']);

        if ($user->hasRole('petugas_provinsi')) {
            $query->where('satker_id', $user->satker_id);
        } elseif ($request->filled('satker_id')) {
            $query->where('satker_id', $request->satker_id);
        }

        if ($request->filled('komoditas')) {
            $query->where('komoditas', $request->komoditas);
        }

        $stokBenihs = $query->latest()->get();

        $pdf = Pdf::loadView('reports.stok_pdf', compact('stokBenihs'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download('Laporan_Stok_Benih_BBPSIP_' . date('Ymd_His') . '.pdf');
    }

    public function exportPanenExcel(Request $request)
    {
        $user = auth()->user();
        $satkerId = $user->hasRole('petugas_provinsi') ? $user->satker_id : $request->query('satker_id');
        $komoditas = $request->query('komoditas');

        $fileName = 'Laporan_Hasil_Panen_BBPSIP_' . date('Ymd_His') . '.xlsx';

        return Excel::download(new CatatanPanenExport($satkerId, $komoditas), $fileName);
    }

    public function exportPanenPdf(Request $request)
    {
        $user = auth()->user();
        $query = CatatanPanen::with(['satker', 'varietas', 'user']);

        if ($user->hasRole('petugas_provinsi')) {
            $query->where('satker_id', $user->satker_id);
        } elseif ($request->filled('satker_id')) {
            $query->where('satker_id', $request->satker_id);
        }

        if ($request->filled('komoditas')) {
            $query->where('komoditas', $request->komoditas);
        }

        $catatanPanens = $query->latest('tanggal_panen')->get();

        $pdf = Pdf::loadView('reports.panen_pdf', compact('catatanPanens'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download('Laporan_Hasil_Panen_BBPSIP_' . date('Ymd_His') . '.pdf');
    }
}
