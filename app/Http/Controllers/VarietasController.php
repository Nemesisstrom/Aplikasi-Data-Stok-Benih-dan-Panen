<?php

namespace App\Http\Controllers;

use App\Models\Varietas;
use Illuminate\Http\Request;

class VarietasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $varietas = Varietas::latest()->paginate(10);
        return view('varietas.index', compact('varietas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_varietas' => 'required|string|max:255',
            'komoditas' => 'required|in:padi,jagung',
            'deskripsi' => 'nullable|string|max:500',
        ]);

        Varietas::create($request->all());

        return redirect()->route('varietas.index')->with('success', 'Data Varietas berhasil ditambahkan!');
    }

    public function update(Request $request, Varietas $varieta)
    {
        $request->validate([
            'nama_varietas' => 'required|string|max:255',
            'komoditas' => 'required|in:padi,jagung',
            'deskripsi' => 'nullable|string|max:500',
        ]);

        $varieta->update($request->all());

        return redirect()->route('varietas.index')->with('success', 'Data Varietas berhasil diperbarui!');
    }

    public function destroy(Varietas $varieta)
    {
        $varieta->delete();
        return redirect()->route('varietas.index')->with('success', 'Data Varietas berhasil dihapus!');
    }
}
