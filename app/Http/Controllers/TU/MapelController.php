<?php

namespace App\Http\Controllers\TU;

use App\Http\Controllers\Controller;
use App\Models\Mapel;
use Illuminate\Http\Request;

class MapelController extends Controller
{
    public function index(Request $request)
    {
        $mapels = Mapel::orderBy('kelompok', 'asc')->orderBy('nama_mapel', 'asc')->get();
        $query = Mapel::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_mapel', 'like', "%{$search}%")
                ->orWhere('nama_mapel', 'like', "%{$search}%");
            });
        }

        $mapels = $query->latest()->paginate(15);
        
        if ($request->has('search')) {
            $mapels->appends(['search' => $request->search]);
        }
        return view('tu.mapel.index', compact('mapels'));
    }

    public function create()
    {
        // Memanggil file tambah.blade.php
        return view('tu.mapel.tambah');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_mapel' => 'required|unique:mapels,kode_mapel',
            'nama_mapel' => 'required',
            'kelompok' => 'required',
            'kkm' => 'required|numeric|min:0|max:100' 
        ]);

        Mapel::create($request->all());
        return redirect()->route('tu.mapel.index')->with('success', 'Mata Pelajaran berhasil ditambahkan!');
    }

    public function edit(string $id)
    {
        $mapel = Mapel::findOrFail($id);
        // Memanggil file edit.blade.php
        return view('tu.mapel.edit', compact('mapel'));
    }

    public function update(Request $request, string $id)
    {
        $mapel = Mapel::findOrFail($id);

        $request->validate([
            'kode_mapel' => 'required|unique:mapels,kode_mapel,' . $mapel->id,
            'nama_mapel' => 'required',
            'kelompok' => 'required',
            'kkm' => 'required|numeric|min:0|max:100' 
        ]);

        $mapel->update($request->all());
        return redirect()->route('tu.mapel.index')->with('success', 'Mata Pelajaran berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        Mapel::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Mata Pelajaran berhasil dihapus!');
    }
}