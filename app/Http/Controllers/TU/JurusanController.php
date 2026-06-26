<?php

namespace App\Http\Controllers\TU;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use Illuminate\Http\Request;

class JurusanController extends Controller
{
    // Menampilkan halaman tabel jurusan
    public function index(Request $request)
    {
        $jurusans = Jurusan::orderBy('created_at', 'desc')->get();
        $query = Jurusan::query();

    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('kode_jurusan', 'like', "%{$search}%")
              ->orWhere('nama_jurusan', 'like', "%{$search}%");
        });
    }

    $jurusans = $query->latest()->paginate(15);
    if ($request->has('search')) {
        $jurusans->appends(['search' => $request->search]);
    }
        return view('tu.jurusan.index', compact('jurusans'));
    }

    // Memproses form tambah jurusan
    

    // Memproses hapus data
    public function destroy($id)
    {
        $jurusan = Jurusan::findOrFail($id);
        $jurusan->delete();

        return redirect()->back()->with('success', 'Data Jurusan berhasil dihapus!');
    }

    public function edit($id)
    {
        $jurusan = \App\Models\Jurusan::findOrFail($id);
        
        return view('tu.jurusan.edit', compact('jurusan'));
    }
    public function create()
    {
        return view('tu.jurusan.tambah');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_jurusan' => 'required|string|max:50|unique:jurusans,kode_jurusan',
            'nama_jurusan' => 'required|string|max:255',
        ], [
            'kode_jurusan.unique' => 'Kode Jurusan ini SUDAH TERDAFTAR! Silakan periksa kembali.',
        ]);

        \App\Models\Jurusan::create([
            'kode_jurusan' => $request->kode_jurusan,
            'nama_jurusan' => $request->nama_jurusan,
        ]);

        return redirect()->route('tu.jurusan.index')->with('success', 'Data Jurusan baru berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_jurusan' => 'required|string|max:50|unique:jurusans,kode_jurusan,' . $id,
            'nama_jurusan' => 'required|string|max:255',
        ], [
            'kode_jurusan.unique' => 'Kode Jurusan ini SUDAH TERDAFTAR! Silakan periksa kembali.',
        ]);

        $jurusan = \App\Models\Jurusan::findOrFail($id);
        $jurusan->update([
            'kode_jurusan' => $request->kode_jurusan,
            'nama_jurusan' => $request->nama_jurusan,
        ]);

        return redirect()->route('tu.jurusan.index')->with('success', 'Data Jurusan berhasil diperbarui!');
    }
}