<?php

namespace App\Http\Controllers\TU;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Str;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        // 1. Mulai query dengan memanggil relasi 'kelas' dan filter status 'Aktif'
        $query = Siswa::with('kelas')->where('status_siswa', 'Aktif');

        // 2. Jika ada request pencarian (search)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            
            // 3. Bungkus orWhere di dalam function() agar status_siswa 'Aktif' tidak tertimpa/bocor
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        // 4. Eksekusi query dengan pagination dan urutkan dari yang terbaru
        $siswas = $query->latest()->paginate(10);

        // 🔥 PERBAIKAN DI SINI: Pasang pengaman (if) sebelum memanggil appends
        if ($request->has('search')) {
            $siswas->appends(['search' => $request->search]);
        }

        // 5. Ambil daftar kelas untuk form pilihan
        $kelases = Kelas::orderBy('nama_kelas', 'asc')->get();

        return view('tu.siswa.index', compact('siswas', 'kelases'));
    }

    public function create()
    {
        $kelases = Kelas::orderBy('nama_kelas', 'asc')->get();
        return view('tu.siswa.tambah', compact('kelases'));
    }

    public function store(Request $request)
{
    // Satpam mengecek kelengkapan data
    $request->validate([
        // 🔥 NISN dikunci max:10 sesuai struktur database
        'nisn'         => 'required|string|max:10|unique:siswas,nisn', 
        // 🔥 Angka sakti email: max:50
        'email'        => 'nullable|email|max:50|unique:users,email', 
        // 🔥 Angka sakti nama: max:35
        'nama_lengkap' => 'required|string|max:35',
        // Validasi tambahan agar tidak error saat relasi
        'kelas_id'     => 'required|exists:kelas,id',
        // Kunci input jenis kelamin hanya L atau P
        'jk'           => 'required|in:L,P',
    ], [
        // --- PESAN ERROR KUSTOM ---
        'nisn.required' => 'NISN wajib diisi.',
        'nisn.max'      => 'NISN maksimal 10 karakter.',
        'nisn.unique'   => 'NISN tersebut sudah terdaftar di sistem!',
        
        'email.email'   => 'Format email tidak valid.',
        'email.max'     => 'Alamat email maksimal 50 karakter.',
        'email.unique'  => 'Email tersebut sudah digunakan oleh akun lain!',
        
        'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
        'nama_lengkap.max'      => 'Nama lengkap maksimal 35 karakter.',
        
        'kelas_id.required' => 'Kelas wajib dipilih.',
        'kelas_id.exists'   => 'Pilihan kelas tidak valid di database.',
        
        'jk.required' => 'Jenis kelamin wajib dipilih.',
        'jk.in'       => 'Pilihan jenis kelamin tidak valid.'
    ]);

        DB::transaction(function () use ($request) {
            
            // 1. Buatkan Akun Login & Masukkan Emailnya
            $user = User::create([
                'name' => $request->nama_lengkap,
                'email' => $request->email, 
                'username' => $request->nisn,
                'password' => Hash::make('budhidarma123'),
                'role' => 'siswa',
            ]);

            // 2. Simpan Biodata Siswa
            Siswa::create([
                'user_id' => $user->id,
                'kelas_id' => $request->kelas_id,
                'nisn' => $request->nisn,
                'nama_lengkap' => $request->nama_lengkap,
                'jk' => $request->jk,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'no_hp_siswa' => $request->no_hp_siswa,
                'nama_orang_tua' => $request->nama_orang_tua, 
                'no_hp_ortu' => $request->no_hp_ortu, 
            ]);
            
        });

        return redirect()->route('tu.siswa.index')->with('success', 'Data Siswa & Akun Login berhasil dibuat!');
    }

    public function edit($id)
    {
        $siswa = Siswa::findOrFail($id);
        $kelases = Kelas::orderBy('nama_kelas', 'asc')->get();
        return view('tu.siswa.edit', compact('siswa', 'kelases'));
    }

    public function update(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);
        $user = User::findOrFail($siswa->user_id);

        // Satpam mengecek kelengkapan data update
    $request->validate([
        // 🔥 NISN max:10, pengecualian ID siswa saat update
        'nisn'         => 'required|string|max:10|unique:siswas,nisn,' . $siswa->id, 
        // 🔥 Aturan baru: Email max:50, pengecualian ID user saat update
        'email'        => 'nullable|email|max:50|unique:users,email,' . $user->id, 
        // 🔥 Nama lengkap max:35
        'nama_lengkap' => 'required|string|max:35',
        // Validasi database agar kelas benar-benar ada
        'kelas_id'     => 'required|exists:kelas,id',
        // Kunci input jenis kelamin hanya L atau P
        'jk'           => 'required|in:L,P',
        'status_siswa' => 'required|string' 
    ], [
        // --- PESAN ERROR KUSTOM ---
        'nisn.required' => 'NISN wajib diisi.',
        'nisn.max'      => 'NISN maksimal 10 karakter.',
        'nisn.unique'   => 'NISN tersebut sudah terdaftar di sistem!',
        
        'email.email'   => 'Format email tidak valid.',
        'email.max'     => 'Alamat email maksimal 50 karakter.',
        'email.unique'  => 'Email tersebut sudah digunakan oleh akun lain!',
        
        'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
        'nama_lengkap.max'      => 'Nama lengkap maksimal 35 karakter.',
        
        'kelas_id.required' => 'Kelas wajib dipilih.',
        'kelas_id.exists'   => 'Pilihan kelas tidak valid di database.',
        
        'jk.required' => 'Jenis kelamin wajib dipilih.',
        'jk.in'       => 'Pilihan jenis kelamin tidak valid.',
        
        'status_siswa.required' => 'Status siswa wajib diisi.',
        'status_siswa.string'   => 'Format status siswa tidak valid.'
    ]);

        DB::transaction(function () use ($request, $siswa, $user) {
            
            // 1. Siapkan data update untuk Akun Login
            $data_user = [
                'name' => $request->nama_lengkap,
                'email' => $request->email,
                'username' => $request->nisn,
            ];

            // Trik Rahasia Pembekuan Akun
            if ($request->status_siswa == 'Pindah') {
                $data_user['password'] = Hash::make(Str::random(40));
            } elseif ($request->status_siswa == 'Aktif' && $siswa->status_siswa == 'Pindah') {
                $data_user['password'] = Hash::make('budhidarma123'); // Password reset
            }

            // Eksekusi update user
            $user->update($data_user);

            // 2. Update Biodata Siswa
            $siswa->update([
                'status_siswa' => $request->status_siswa, 
                'kelas_id' => $request->kelas_id,
                'nisn' => $request->nisn,
                'nama_lengkap' => $request->nama_lengkap,
                'jk' => $request->jk,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'nama_orang_tua' => $request->nama_orang_tua,
                'no_hp_ortu' => $request->no_hp_ortu,
                'no_hp_siswa' => $request->no_hp_siswa, 
            ]);
            
        });

        return redirect()->route('tu.siswa.index')->with('success', 'Data Siswa & Akun Login berhasil diperbarui!');
    }

    public function show($id)
    {
        $siswa = Siswa::with(['kelas', 'user'])->findOrFail($id);
        return view('tu.siswa.show', compact('siswa'));
    }

    public function destroy($id)
    {
        $siswa = Siswa::findOrFail($id);
        User::where('id', $siswa->user_id)->delete(); 
        $siswa->delete();

        return redirect()->back()->with('success', 'Data Siswa berhasil dihapus!');
    }
}