<?php

namespace App\Http\Controllers;

use App\Models\StokBenih;
use App\Models\Satker;
use App\Models\Varietas;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class StokBenihController extends Controller implements HasMiddleware
{
    /**
     * Middleware Definition untuk Laravel 11/13
     */
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('role:admin_pusat|petugas_provinsi', except: ['index', 'show', 'getVarietasByKomoditas']),
        ];
    }

    /**
     * Tampilkan daftar stok benih (Admin Pusat melihat seluruh Satker)
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = StokBenih::with(['satker', 'varietas']);

        // Jika petugas daerah, kunci data hanya untuk Satker miliknya
        if ($user->hasRole('petugas_provinsi')) {
            $query->where('satker_id', $user->satker_id);
        } elseif ($request->filled('satker_id')) {
            $query->where('satker_id', $request->satker_id);
        }

        // Filter Komoditas
        if ($request->filled('komoditas')) {
            $query->where('komoditas', $request->komoditas);
        }

        // Filter Status Stok
        if ($request->filled('status_stok')) {
            $query->where('status_stok', $request->status_stok);
        }

        $stokList = $query->latest()->paginate(15);
        $satkers = Satker::all();
        $varietas = Varietas::all();

        return view('stok_benih.index', compact('stokList', 'satkers', 'varietas'));
    }

    /**
     * Form Tambah Stok
     */
    public function create()
    {
        $satkers = Satker::all();
        $varietasPadi = Varietas::where('komoditas', 'padi')->get();
        $varietasJagung = Varietas::where('komoditas', 'jagung')->get();

        return view('stok_benih.create', compact('satkers', 'varietasPadi', 'varietasJagung'));
    }

    /**
     * Simpan Data Stok Baru
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'komoditas' => 'required|in:padi,jagung',
            'varietas_id' => 'required|exists:varietas,id',
            'kelas_benih' => 'required|in:BS,FS,SS,ES',
            'volume_ton' => 'required|numeric|min:0.01',
            'status_stok' => 'required|in:persiapan_panen,prosesing,proses_sertifikasi,tersedia_tersertifikasi',
            'nomor_sertifikat' => 'nullable|string|max:100',
            'tanggal_panen_proses' => 'nullable|date',
            'catatan' => 'nullable|string',
        ];

        if ($user->hasRole('admin_pusat')) {
            $rules['satker_id'] = 'required|exists:satkers,id';
        }

        $validated = $request->validate($rules);
        $validated['satker_id'] = $user->hasRole('petugas_provinsi') ? $user->satker_id : $validated['satker_id'];

        StokBenih::create($validated);

        return redirect()->route('stok-benih.index')->with('success', 'Data stok benih berhasil ditambahkan.');
    }

    /**
     * Form Edit Data Stok
     */
    public function edit(StokBenih $stokBenih)
    {
        $user = auth()->user();
        if ($user->hasRole('petugas_provinsi') && $stokBenih->satker_id !== $user->satker_id) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengubah data Satker lain.');
        }

        $satkers = Satker::all();
        $varietas = Varietas::where('komoditas', $stokBenih->komoditas)->get();

        return view('stok_benih.edit', compact('stokBenih', 'satkers', 'varietas'));
    }

    /**
     * Update Data Stok
     */
    public function update(Request $request, StokBenih $stokBenih)
    {
        $user = auth()->user();
        if ($user->hasRole('petugas_provinsi') && $stokBenih->satker_id !== $user->satker_id) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengubah data Satker lain.');
        }

        $rules = [
            'komoditas' => 'required|in:padi,jagung',
            'varietas_id' => 'required|exists:varietas,id',
            'kelas_benih' => 'required|in:BS,FS,SS,ES',
            'volume_ton' => 'required|numeric|min:0.01',
            'status_stok' => 'required|in:persiapan_panen,prosesing,proses_sertifikasi,tersedia_tersertifikasi',
            'nomor_sertifikat' => 'nullable|string|max:100',
            'tanggal_panen_proses' => 'nullable|date',
            'catatan' => 'nullable|string',
        ];

        if ($user->hasRole('admin_pusat')) {
            $rules['satker_id'] = 'required|exists:satkers,id';
        }

        $validated = $request->validate($rules);
        $stokBenih->update($validated);

        return redirect()->route('stok-benih.index')->with('success', 'Data stok benih berhasil diperbarui.');
    }

    /**
     * Hapus Data Stok
     */
    public function destroy(StokBenih $stokBenih)
    {
        $user = auth()->user();
        if ($user->hasRole('petugas_provinsi') && $stokBenih->satker_id !== $user->satker_id) {
            abort(403, 'Anda tidak memiliki hak akses untuk menghapus data Satker lain.');
        }

        $stokBenih->delete();
        return redirect()->route('stok-benih.index')->with('success', 'Data stok benih berhasil dihapus.');
    }

    /**
     * API AJAX Dynamic Dropdown Varietas
     */
    public function getVarietasByKomoditas($komoditas)
    {
        $data = Varietas::where('komoditas', $komoditas)->get();
        return response()->json($data);
    }
}
