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
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('role:admin_pusat|petugas_provinsi', except: ['index', 'show', 'getVarietasByKomoditas']),
        ];
    }

    public function index(Request $request)
    {
        $user = auth()->user();

        $query = StokBenih::with([
            'satker:id,kode_satker,nama_satker',
            'varietas:id,nama_varietas,komoditas'
        ]);

        if ($user->hasRole('petugas_provinsi')) {
            $query->where('satker_id', $user->satker_id);
        } elseif ($request->filled('satker_id')) {
            $query->where('satker_id', $request->satker_id);
        }

        if ($request->filled('komoditas')) {
            $query->where('komoditas', $request->komoditas);
        }

        if ($request->filled('status_stok')) {
            $query->where('status_stok', $request->status_stok);
        }

        $stokList = $query->latest()->paginate(15)->withQueryString();

        $satkers = cache()->remember('satkers_all', 3600, fn() => Satker::all());
        $varietas = cache()->remember('varietas_all', 3600, fn() => Varietas::all());

        return view('stok_benih.index', compact('stokList', 'satkers', 'varietas'));
    }

    public function create()
    {
        $satkers = cache()->remember('satkers_all', 3600, fn() => Satker::all());
        $varietasPadi = Varietas::where('komoditas', 'padi')->get();
        $varietasJagung = Varietas::where('komoditas', 'jagung')->get();

        return view('stok_benih.create', compact('satkers', 'varietasPadi', 'varietasJagung'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'komoditas' => 'required|in:padi,jagung',
            'varietas_id' => 'required|exists:varietas,id',
            'kelas_benih' => 'required|in:BS,FS,SS,ES',
            'volume_ton' => 'required|numeric|min:0.01',
            'status_stok' => 'required|in:persiapan_panen,prosesing,proses_sertifikasi,tersedia_tersertifikasi',
            'nomor_sertifikat' => 'nullable|string|max:100',
            'tanggal_panen_proses' => 'nullable|date',
            'catatan' => 'nullable|string',
            'satker_id' => $user->hasRole('admin_pusat') ? 'required|exists:satkers,id' : 'nullable',
        ]);

        $validated['satker_id'] = $user->hasRole('petugas_provinsi') ? $user->satker_id : $validated['satker_id'];

        StokBenih::create($validated);

        return redirect()->route('stok-benih.index')->with('success', 'Data stok benih berhasil ditambahkan.');
    }

    public function edit(StokBenih $stokBenih)
    {
        $user = auth()->user();
        if ($user->hasRole('petugas_provinsi') && $stokBenih->satker_id !== $user->satker_id) {
            abort(403, 'Akses ditolak.');
        }

        $satkers = Satker::all();
        $varietas = Varietas::where('komoditas', $stokBenih->komoditas)->get();

        return view('stok_benih.edit', compact('stokBenih', 'satkers', 'varietas'));
    }

    public function update(Request $request, StokBenih $stokBenih)
    {
        $user = auth()->user();
        if ($user->hasRole('petugas_provinsi') && $stokBenih->satker_id !== $user->satker_id) {
            abort(403, 'Akses ditolak.');
        }

        $validated = $request->validate([
            'komoditas' => 'required|in:padi,jagung',
            'varietas_id' => 'required|exists:varietas,id',
            'kelas_benih' => 'required|in:BS,FS,SS,ES',
            'volume_ton' => 'required|numeric|min:0.01',
            'status_stok' => 'required|in:persiapan_panen,prosesing,proses_sertifikasi,tersedia_tersertifikasi',
            'nomor_sertifikat' => 'nullable|string|max:100',
            'tanggal_panen_proses' => 'nullable|date',
            'catatan' => 'nullable|string',
            'satker_id' => $user->hasRole('admin_pusat') ? 'required|exists:satkers,id' : 'nullable',
        ]);

        if ($user->hasRole('petugas_provinsi')) {
            unset($validated['satker_id']);
        }

        $stokBenih->update($validated);

        return redirect()->route('stok-benih.index')->with('success', 'Data stok benih berhasil diperbarui.');
    }

    public function destroy(StokBenih $stokBenih)
    {
        $user = auth()->user();
        if ($user->hasRole('petugas_provinsi') && $stokBenih->satker_id !== $user->satker_id) {
            abort(403, 'Akses ditolak.');
        }

        $stokBenih->delete();
        return redirect()->route('stok-benih.index')->with('success', 'Data stok benih berhasil dihapus.');
    }

    public function getVarietasByKomoditas($komoditas)
    {
        return response()->json(Varietas::where('komoditas', $komoditas)->select('id', 'nama_varietas')->get());
    }
}
