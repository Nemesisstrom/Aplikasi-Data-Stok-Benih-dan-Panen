<?php

namespace App\Http\Controllers;

use App\Models\StokBenih;
use App\Models\CatatanPanen;
use App\Models\Satker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $stokQuery = StokBenih::query();
        $panenQuery = CatatanPanen::query();

        if ($user->hasRole('petugas_provinsi') || $user->satker_id) {
            $stokQuery->where('satker_id', $user->satker_id);
            $panenQuery->where('satker_id', $user->satker_id);
        }

        $padiTersedia = (clone $stokQuery)->where('komoditas', 'padi')->where('status_stok', 'tersedia_tersertifikasi')->sum('volume_ton');
        $padiProses = (clone $stokQuery)->where('komoditas', 'padi')->whereIn('status_stok', ['persiapan_panen', 'prosesing', 'proses_sertifikasi'])->sum('volume_ton');
        $jagungTersedia = (clone $stokQuery)->where('komoditas', 'jagung')->where('status_stok', 'tersedia_tersertifikasi')->sum('volume_ton');
        $jagungProses = (clone $stokQuery)->where('komoditas', 'jagung')->whereIn('status_stok', ['persiapan_panen', 'prosesing', 'proses_sertifikasi'])->sum('volume_ton');

        $padiPotensiPanen = (clone $stokQuery)->where('komoditas', 'padi')->where('status_stok', 'persiapan_panen')->sum('volume_ton');
        $jagungPotensiPanen = (clone $stokQuery)->where('komoditas', 'jagung')->where('status_stok', 'persiapan_panen')->sum('volume_ton');

        $totalPanenPadi = (clone $panenQuery)->where('komoditas', 'padi')->sum('jumlah_panen_ton');
        $totalPanenJagung = (clone $panenQuery)->where('komoditas', 'jagung')->sum('jumlah_panen_ton');

        $panenBulanan = (clone $panenQuery)
            ->select(DB::raw("MONTH(tanggal_panen) as bulan"), DB::raw("SUM(jumlah_panen_ton) as total_panen"))
            ->where('komoditas', 'padi')
            ->whereYear('tanggal_panen', date('Y'))
            ->groupBy(DB::raw("MONTH(tanggal_panen)"))
            ->pluck('total_panen', 'bulan')
            ->toArray();

        $labelsBulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $dataPanen = [];
        for ($i = 1; $i <= 12; $i++) {
            $dataPanen[] = round($panenBulanan[$i] ?? 0, 2);
        }

        $kelasStok = (clone $stokQuery)
            ->select('kelas_benih', DB::raw('SUM(volume_ton) as total_volume'))
            ->groupBy('kelas_benih')
            ->pluck('total_volume', 'kelas_benih')
            ->toArray();

        $dataKelas = [
            'BS' => round($kelasStok['BS'] ?? 0, 2),
            'FS' => round($kelasStok['FS'] ?? 0, 2),
            'SS' => round($kelasStok['SS'] ?? 0, 2),
            'ES' => round($kelasStok['ES'] ?? 0, 2),
        ];

        $rekapSatker = null;
        if ($user->hasAnyRole(['admin_pusat', 'pimpinan'])) {
            $rekapSatker = Satker::withSum([
                'stokBenihs as stok_padi_tersedia' => fn($q) => $q->where('komoditas', 'padi')->where('status_stok', 'tersedia_tersertifikasi')
            ], 'volume_ton')
            ->withSum([
                'stokBenihs as stok_jagung_tersedia' => fn($q) => $q->where('komoditas', 'jagung')->where('status_stok', 'tersedia_tersertifikasi')
            ], 'volume_ton')
            ->get();
        }

        return view('dashboard.index', compact(
            'padiTersedia', 'padiProses', 'jagungTersedia', 'jagungProses',
            'padiPotensiPanen', 'jagungPotensiPanen', 'totalPanenPadi', 'totalPanenJagung',
            'labelsBulan', 'dataPanen', 'dataKelas', 'rekapSatker'
        ));
    }

    public function exportPdf(Request $request)
    {
        $user = auth()->user();

        $stokQuery = StokBenih::query();
        $panenQuery = CatatanPanen::query();

        if ($user->hasRole('petugas_provinsi') || $user->satker_id) {
            $stokQuery->where('satker_id', $user->satker_id);
            $panenQuery->where('satker_id', $user->satker_id);
        }

        $padiTersedia = (clone $stokQuery)->where('komoditas', 'padi')->where('status_stok', 'tersedia_tersertifikasi')->sum('volume_ton');
        $padiProses = (clone $stokQuery)->where('komoditas', 'padi')->whereIn('status_stok', ['persiapan_panen', 'prosesing', 'proses_sertifikasi'])->sum('volume_ton');
        $jagungTersedia = (clone $stokQuery)->where('komoditas', 'jagung')->where('status_stok', 'tersedia_tersertifikasi')->sum('volume_ton');
        $jagungProses = (clone $stokQuery)->where('komoditas', 'jagung')->whereIn('status_stok', ['persiapan_panen', 'prosesing', 'proses_sertifikasi'])->sum('volume_ton');

        $totalPanenPadi = (clone $panenQuery)->where('komoditas', 'padi')->sum('jumlah_panen_ton');
        $totalPanenJagung = (clone $panenQuery)->where('komoditas', 'jagung')->sum('jumlah_panen_ton');

        $rekapSatker = null;
        if ($user->hasAnyRole(['admin_pusat', 'pimpinan'])) {
            $rekapSatker = Satker::withSum([
                'stokBenihs as stok_padi_tersedia' => fn($q) => $q->where('komoditas', 'padi')->where('status_stok', 'tersedia_tersertifikasi')
            ], 'volume_ton')
            ->withSum([
                'stokBenihs as stok_jagung_tersedia' => fn($q) => $q->where('komoditas', 'jagung')->where('status_stok', 'tersedia_tersertifikasi')
            ], 'volume_ton')
            ->get();
        }

        // Definisikan variabel penampung grafik agar tidak Undefined variable
        $lineChartBase64 = null;
        $donutChartBase64 = null;

        $pdf = Pdf::loadView('reports.dashboard_pdf', compact(
            'padiTersedia', 'padiProses', 'jagungTersedia', 'jagungProses',
            'totalPanenPadi', 'totalPanenJagung', 'rekapSatker',
            'lineChartBase64', 'donutChartBase64'
        ))->setPaper('a4', 'portrait');

        return $pdf->download('Laporan_Ringkasan_Dashboard_' . date('Ymd_His') . '.pdf');
    }
}
