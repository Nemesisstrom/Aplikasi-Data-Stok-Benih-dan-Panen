<?php

namespace App\Http\Controllers;

use App\Models\CatatanPanen;
use App\Models\Satker;
use App\Models\Varietas;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CatatanPanenController extends Controller implements HasMiddleware
{
    /**
     * Middleware Definition untuk Laravel 11/13
     */
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('role:admin_pusat|petugas_provinsi', except: ['index']),
        ];
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $query = CatatanPanen::with(['satker', 'varietas', 'user']);

        // Jika petugas provinsi, kunci data hanya untuk satker miliknya
        if ($user->hasRole('petugas_provinsi')) {
            $query->where('satker_id', $user->satker_id);
        } elseif ($request->filled('satker_id')) {
            $query->where('satker_id', $request->satker_id);
        }

        // Filter Komoditas jika ada
        if ($request->filled('komoditas')) {
            $query->where('komoditas', $request->komoditas);
        }

        // Filter Tahun jika ada
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_panen', $request->tahun);
        }

        $panenList = $query->latest('tanggal_panen')->paginate(15);
        $satkers = Satker::all();

        return view('catatan_panen.index', compact('panenList', 'satkers'));
    }

    public function create()
    {
        $satkers = Satker::all();
        $varietasPadi = Varietas::where('komoditas', 'padi')->get();
        $varietasJagung = Varietas::where('komoditas', 'jagung')->get();

        return view('catatan_panen.create', compact('satkers', 'varietasPadi', 'varietasJagung'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'komoditas' => 'required|in:padi,jagung',
            'varietas_id' => 'required|exists:varietas,id',
            'luas_lahan_ha' => 'required|numeric|min:0.01',
            'jumlah_panen_ton' => 'required|numeric|min:0.01',
            'tanggal_panen' => 'required|date',
            'lokasi_lahan' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ];

        if ($user->hasRole('admin_pusat')) {
            $rules['satker_id'] = 'required|exists:satkers,id';
        }

        $validated = $request->validate($rules);

        CatatanPanen::create([
            'satker_id' => $user->hasRole('petugas_provinsi') ? $user->satker_id : $validated['satker_id'],
            'varietas_id' => $validated['varietas_id'],
            'user_id' => $user->id,
            'komoditas' => $validated['komoditas'],
            'luas_lahan_ha' => $validated['luas_lahan_ha'],
            'jumlah_panen_ton' => $validated['jumlah_panen_ton'],
            'tanggal_panen' => $validated['tanggal_panen'],
            'lokasi_lahan' => $validated['lokasi_lahan'] ?? null,
            'catatan' => $validated['catatan'] ?? null,
        ]);

        return redirect()->route('catatan-panen.index')->with('success', 'Catatan hasil panen berhasil ditambahkan.');
    }

    public function edit(CatatanPanen $catatanPanen)
    {
        $user = auth()->user();
        if ($user->hasRole('petugas_provinsi') && $catatanPanen->satker_id !== $user->satker_id) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengubah data ini.');
        }

        $satkers = Satker::all();
        $varietas = Varietas::where('komoditas', $catatanPanen->komoditas)->get();

        return view('catatan_panen.edit', compact('catatanPanen', 'satkers', 'varietas'));
    }

    public function update(Request $request, CatatanPanen $catatanPanen)
    {
        $user = auth()->user();
        if ($user->hasRole('petugas_provinsi') && $catatanPanen->satker_id !== $user->satker_id) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengubah data ini.');
        }

        $rules = [
            'komoditas' => 'required|in:padi,jagung',
            'varietas_id' => 'required|exists:varietas,id',
            'luas_lahan_ha' => 'required|numeric|min:0.01',
            'jumlah_panen_ton' => 'required|numeric|min:0.01',
            'tanggal_panen' => 'required|date',
            'lokasi_lahan' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ];

        if ($user->hasRole('admin_pusat')) {
            $rules['satker_id'] = 'required|exists:satkers,id';
        }

        $validated = $request->validate($rules);
        $catatanPanen->update($validated);

        return redirect()->route('catatan-panen.index')->with('success', 'Catatan panen berhasil diperbarui.');
    }

    public function destroy(CatatanPanen $catatanPanen)
    {
        $user = auth()->user();
        if ($user->hasRole('petugas_provinsi') && $catatanPanen->satker_id !== $user->satker_id) {
            abort(403, 'Anda tidak memiliki hak akses untuk menghapus data ini.');
        }

        $catatanPanen->delete();
        return redirect()->route('catatan-panen.index')->with('success', 'Catatan panen berhasil dihapus.');
    }
}
