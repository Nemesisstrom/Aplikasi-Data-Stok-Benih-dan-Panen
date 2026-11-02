<?php

namespace App\Http\Controllers;

use App\Models\StokBenih;
use App\Models\CatatanPanen;
use App\Models\Satker;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // HAPUS method __construct() yang berisi $this->middleware('auth');
    // karena middleware auth sudah aktif via routes/web.php

    public function index()
    {
        $user = auth()->user();

        // 1. Query Dasar Terfilter berdasarkan Role User
        $stokQuery = StokBenih::query();
        $panenQuery = CatatanPanen::query();

        // Jika petugas daerah/provinsi, batasi hanya untuk data Satker miliknya
        if ($user->hasRole('petugas_provinsi') || $user->satker_id) {
            $stokQuery->where('satker_id', $user->satker_id);
            $panenQuery->where('satker_id', $user->satker_id);
        }

        // 2. Ringkasan Agregat Kartu Statistik
        $padiTersedia = (clone $stokQuery)
            ->where('komoditas', 'padi')
            ->where('status_stok', 'tersedia_tersertifikasi')
            ->sum('volume_ton');

        $padiProses = (clone $stokQuery)
            ->where('komoditas', 'padi')
            ->whereIn('status_stok', ['persiapan_panen', 'prosesing', 'proses_sertifikasi'])
            ->sum('volume_ton');

        $jagungTersedia = (clone $stokQuery)
            ->where('komoditas', 'jagung')
            ->where('status_stok', 'tersedia_tersertifikasi')
            ->sum('volume_ton');

        $jagungProses = (clone $stokQuery)
            ->where('komoditas', 'jagung')
            ->whereIn('status_stok', ['persiapan_panen', 'prosesing', 'proses_sertifikasi'])
            ->sum('volume_ton');

        $padiPotensiPanen = (clone $stokQuery)
            ->where('komoditas', 'padi')
            ->where('status_stok', 'persiapan_panen')
            ->sum('volume_ton');

        $jagungPotensiPanen = (clone $stokQuery)
            ->where('komoditas', 'jagung')
            ->where('status_stok', 'persiapan_panen')
            ->sum('volume_ton');

        $totalPanenPadi = (clone $panenQuery)
            ->where('komoditas', 'padi')
            ->sum('jumlah_panen_ton');

        $totalPanenJagung = (clone $panenQuery)
            ->where('komoditas', 'jagung')
            ->sum('jumlah_panen_ton');

        // 3. Data Line Chart: Tren Panen Bulanan Tahun Berjalan
        $panenBulanan = (clone $panenQuery)
            ->select(
                DB::raw("MONTH(tanggal_panen) as bulan"),
                DB::raw("SUM(jumlah_panen_ton) as total_panen")
            )
            ->where('komoditas', 'padi')
            ->whereYear('tanggal_panen', date('Y'))
            ->groupBy(DB::raw("MONTH(tanggal_panen)"))
            ->orderBy('bulan', 'asc')
            ->pluck('total_panen', 'bulan')
            ->toArray();

        $labelsBulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $dataPanen = [];
        for ($i = 1; $i <= 12; $i++) {
            $dataPanen[] = round($panenBulanan[$i] ?? 0, 2);
        }

        // 4. Data Donut Chart: Stok per Kelas Benih (BS, FS, SS, ES)
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

        // 5. Ringkasan per Satker (Khusus Admin & Pimpinan)
        $rekapSatker = null;
        if ($user->hasAnyRole(['admin_pusat', 'pimpinan'])) {
            $rekapSatker = Satker::withSum(['stokBenihs as stok_padi_tersedia' => function($q) {
                $q->where('komoditas', 'padi')->where('status_stok', 'tersedia_tersertifikasi');
            }], 'volume_ton')
            ->withSum(['stokBenihs as stok_jagung_tersedia' => function($q) {
                $q->where('komoditas', 'jagung')->where('status_stok', 'tersedia_tersertifikasi');
            }], 'volume_ton')
            ->get();
        }

        return view('dashboard.index', compact(
            'padiTersedia',
            'padiProses',
            'jagungTersedia',
            'jagungProses',
            'padiPotensiPanen',
            'jagungPotensiPanen',
            'totalPanenPadi',
            'totalPanenJagung',
            'labelsBulan',
            'dataPanen',
            'dataKelas',
            'rekapSatker'
        ));
    }

    public function exportPdf(Request $request)
    {
        $lineChartBase64 = $request->input('line_chart_img');
        $donutChartBase64 = $request->input('donut_chart_img');

        $padiTersedia = StokBenih::where('komoditas', 'padi')
            ->where('status_stok', 'tersedia_tersertifikasi')
            ->sum('volume_ton');

        $jagungTersedia = StokBenih::where('komoditas', 'jagung')
            ->where('status_stok', 'tersedia_tersertifikasi')
            ->sum('volume_ton');

        $totalPanenPadi = CatatanPanen::where('komoditas', 'padi')->sum('jumlah_panen_ton');
        $totalPanenJagung = CatatanPanen::where('komoditas', 'jagung')->sum('jumlah_panen_ton');

        $pdf = Pdf::loadView('reports.dashboard_pdf', compact(
            'lineChartBase64',
            'donutChartBase64',
            'padiTersedia',
            'jagungTersedia',
            'totalPanenPadi',
            'totalPanenJagung'
        ))->setPaper('a4', 'portrait');

        return $pdf->download('Ringkasan_Dashboard_BBPSIP_' . date('Ymd_His') . '.pdf');
    }
}
