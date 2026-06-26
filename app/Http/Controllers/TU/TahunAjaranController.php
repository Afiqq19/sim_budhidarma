<?php

namespace App\Http\Controllers\TU;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran; // Pastikan model sudah ada
use Illuminate\Http\Request;

class TahunAjaranController extends Controller
{
    public function index()
    {
        $tahun_ajarans = TahunAjaran::orderBy('tahun', 'desc')->orderBy('semester', 'desc')->get();
        return view('tu.tahun_ajaran.index', compact('tahun_ajarans'));
    }
    public function create()
    {
        return view('tu.tahun_ajaran.tambah');
    }
    public function store(Request $request)
    {
        $request->validate([
            'tahun' => 'required',
            'semester' => 'required',
        ]);

        TahunAjaran::create($request->all());

        // UBAH redirect()->back() MENJADI redirect()->route()
        return redirect()->route('tu.tahun-ajaran.index')->with('success', 'Tahun Ajaran berhasil ditambahkan!');
    }

    // Fungsi khusus untuk mengaktifkan Tahun Ajaran
    public function setAktif($id)
    {
        // 1. Matikan semua tahun ajaran yang ada
        TahunAjaran::query()->update(['is_active' => false]);

        // 2. Aktifkan hanya yang dipilih
        $ta = TahunAjaran::findOrFail($id);
        $ta->update(['is_active' => true]);

        return redirect()->back()->with('success', 'Tahun Ajaran ' . $ta->tahun . ' Semester ' . $ta->semester . ' berhasil diAKTIFkan!');
    }

    public function destroy($id)
    {
        $ta = TahunAjaran::findOrFail($id);
        
        if ($ta->is_active) {
            return redirect()->back()->withErrors(['Gagal! Tahun Ajaran yang sedang Aktif tidak boleh dihapus.']);
        }

        $ta->delete();
        return redirect()->back()->with('success', 'Tahun Ajaran berhasil dihapus!');
    }
    public function edit($id)
    {
        $ta = TahunAjaran::findOrFail($id);
        return view('tu.tahun_ajaran.edit', compact('ta'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tahun' => 'required',
            'semester' => 'required',
        ]);

        $ta = TahunAjaran::findOrFail($id);
        $ta->update($request->all());

        return redirect()->route('tu.tahun-ajaran.index')->with('success', 'Tahun Ajaran berhasil diperbarui!');
    }
}