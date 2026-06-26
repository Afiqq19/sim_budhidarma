<?php

namespace App\Http\Controllers\TU;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Jurusan; 
use App\Models\WaliKelas; // 🔥 WAJIB DIPANGGIL: Model Wali Kelas 🔥
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        // 1. Mulai query dengan memanggil relasi 'jurusan' (Eager Loading)
        $query = Kelas::with('jurusan');

        // 2. Jika ada request pencarian (search)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            // Gunakan kurung closure (function) agar aman jika nanti ada tambahan filter (seperti status aktif dsb)
            $query->where(function($q) use ($search) {
                $q->where('kode_kelas', 'like', "%{$search}%")
                  ->orWhere('nama_kelas', 'like', "%{$search}%");
            });
        }
        
        // 3. Eksekusi query dengan urutan terbaru dan gunakan paginate!
        $kelas = $query->latest()->paginate(15);

        // 4. Agar query pencarian tidak ter-reset saat klik halaman 2, 3, dst
        if ($request->has('search')) {
            $kelas->appends(['search' => $request->search]);
        }

        // 5. Ambil data jurusan untuk pilihan di form (Dropdown)
        $jurusans = Jurusan::orderBy('nama_jurusan', 'asc')->get();
        
        return view('tu.kelas.index', compact('kelas', 'jurusans'));
    }

    public function create()
    {
        $jurusans = Jurusan::all();
        $walikelases = WaliKelas::all(); // 🔥 Lempar data Wali Kelas ke form Tambah 🔥
        
        return view('tu.kelas.tambah', compact('jurusans', 'walikelases'));
    }

    public function store(Request $request)
    {
        // 1. Satpam Validasi
        $request->validate([
            'jurusan_id' => 'required',
            'kode_kelas' => 'required|unique:kelas,kode_kelas', // <-- Aturan tidak boleh kembar
            'nama_kelas' => 'required',
        ], [
            // Pesan Error Custom (Biar Admin tidak bingung bahasa Inggris)
            'kode_kelas.unique' => 'Oops! Kode Kelas ini SUDAH ADA di sistem. Gunakan kode lain.',
        ]);

        // 2. Simpan Data Kelas Baru
        $kelas = Kelas::create([
            'jurusan_id' => $request->jurusan_id,
            'kode_kelas' => $request->kode_kelas,
            'nama_kelas' => $request->nama_kelas,
        ]);

        // 3. 🔥 Logika Pintar: Simpan Penetapan Wali Kelas 🔥
        if ($request->filled('walikelas_id')) {
            $walikelas = WaliKelas::find($request->walikelas_id);
            if ($walikelas) {
                // Update kelas_id di tabel wali_kelas
                $walikelas->kelas_id = $kelas->id;
                $walikelas->save();
            }
        }

        return redirect()->route('tu.kelas.index')->with('success', 'Mantap! Data Kelas baru dan Penetapan Wali Kelas berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $kelas = Kelas::findOrFail($id);
        $jurusans = Jurusan::all(); // Ambil data jurusan untuk pilihan dropdown
        $walikelases = WaliKelas::all(); // 🔥 Lempar data Wali Kelas ke form Edit 🔥
        
        return view('tu.kelas.edit', compact('kelas', 'jurusans', 'walikelases'));
    }

    public function update(Request $request, $id)
    {
        // 1. Satpam Validasi (Pengecualian ID agar bisa diedit tanpa error)
        $request->validate([
            'jurusan_id' => 'required',
            'kode_kelas' => 'required|unique:kelas,kode_kelas,' . $id, 
            'nama_kelas' => 'required',
        ], [
            'kode_kelas.unique' => 'Oops! Kode Kelas ini SUDAH ADA di sistem. Gunakan kode lain.',
        ]);

        // 2. Update Data Kelas
        $kelas = Kelas::findOrFail($id);
        $kelas->update([
            'jurusan_id' => $request->jurusan_id,
            'kode_kelas' => $request->kode_kelas,
            'nama_kelas' => $request->nama_kelas,
        ]);

        // 3. 🔥 Logika Pintar: Update Penetapan Wali Kelas 🔥
        // Copot dulu jabatan wali kelas dari guru yang sebelumnya mengampu kelas ini (jika ada)
        WaliKelas::where('kelas_id', $kelas->id)->update(['kelas_id' => null]);

        // Jika TU memilih guru baru (atau guru yang sama) di form, tetapkan ulang kelasnya
        if ($request->filled('walikelas_id')) {
            $walikelas = WaliKelas::find($request->walikelas_id);
            if ($walikelas) {
                $walikelas->kelas_id = $kelas->id;
                $walikelas->save();
            }
        }

        return redirect()->route('tu.kelas.index')->with('success', 'Data Kelas dan Penetapan Wali Kelas berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);
        
        // Catatan: Jika relasi di database pakai onDelete('CASCADE') atau diset ke null, 
        // data wali_kelas.kelas_id otomatis akan bersih. Jika tidak, bisa dikosongkan manual:
        WaliKelas::where('kelas_id', $id)->update(['kelas_id' => null]);
        
        $kelas->delete();

        return redirect()->back()->with('success', 'Data Kelas berhasil dihapus!');
    }
}