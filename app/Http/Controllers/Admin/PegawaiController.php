<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PegawaiController extends Controller
{
    public function index()
    {
        // Ambil data pegawai beserta akun login-nya
        $pegawais = Pegawai::with('user')->latest()->get();
        return view('admin.pegawai.index', compact('pegawais'));
    }

    public function create()
    {
        return view('admin.pegawai.tambah');
    }

    public function store(Request $request)
{
    $request->validate([
        // 🔥 Sesuaikan max menjadi 50 agar sinkron dengan database
        'nama_lengkap' => 'required|string|max:35',
        'jk'           => 'required|in:L,P',
        // 🔥 Tambahkan max:40 untuk jabatan
        'jabatan'      => 'required|string|max:40',
        // 🔥 Tambahkan max:50 untuk email
        'email'        => 'required|email|max:50|unique:users,email', 
        // 🔥 Tambahkan max:35 untuk username
        'username'     => 'required|string|max:35|unique:users,username',
        'password'     => 'required|string|min:6',
        'role'         => 'required|in:tu,bendahara',
    ], [
        // --- PESAN ERROR KUSTOM YANG RAMAH PENGGUNA ---
        'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
        'nama_lengkap.max'      => 'Nama lengkap terlalu panjang (maksimal 35 karakter).',
        
        'jk.required'  => 'Jenis kelamin wajib dipilih.',
        'jk.in'        => 'Jenis kelamin tidak valid.',
        
        'jabatan.required' => 'Jabatan wajib diisi.',
        'jabatan.max'      => 'Nama jabatan terlalu panjang (maksimal 40 karakter).',
        
        'email.required' => 'Email wajib diisi.',
        'email.email'  => 'Format email tidak valid.',
        'email.unique' => 'Email sudah terdaftar, gunakan email lain.',
        'email.max'    => 'Alamat email terlalu panjang (maksimal 50 karakter).',
        
        'username.required' => 'Username wajib diisi.',
        'username.unique'   => 'Username sudah dipakai, silakan cari kombinasi lain.',
        'username.max'      => 'Username terlalu panjang (maksimal 35 karakter).',
        
        'password.required' => 'Password wajib diisi.',
        'password.min'      => 'Password terlalu pendek (minimal 6 karakter).',
        
        'role.required' => 'Hak akses (Role) wajib dipilih.',
        'role.in'       => 'Pilihan hak akses tidak valid.'
    ]);

    DB::beginTransaction();
    try {
        // 1. Buat Akun Login
        $user = User::create([
            'name'     => $request->nama_lengkap,
            'email'    => $request->email, // 🔥 Masuk ke tabel users
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        // 2. Buat Profil Pegawai
        Pegawai::create([
            'user_id'      => $user->id,
            'nip'          => $request->nip, // Opsional
            'nama_lengkap' => $request->nama_lengkap,
            'jk'           => $request->jk,
            'no_hp'        => $request->no_hp,
            'alamat'       => $request->alamat,
            'jabatan'      => $request->jabatan,
        ]);

        DB::commit();
        return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil didaftarkan!');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Gagal: ' . $e->getMessage());
    }
}
public function edit($id)
    {
        // Ambil data pegawai beserta akun user-nya
        $pegawai = Pegawai::with('user')->findOrFail($id);
        return view('admin.pegawai.edit', compact('pegawai'));
    }

    public function update(Request $request, $id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $user = User::findOrFail($pegawai->user_id);

        // Validasi (Pengecualian unique untuk email & username milik sendiri)
        $request->validate([
        // 🔥 Setia pada angka sakti Bapak: max:35
        'nama_lengkap' => 'required|string|max:35',
        'jk'           => 'required|in:L,P',
        // 🔥 Jabatan disesuaikan ke max:40 agar seragam
        'jabatan'      => 'required|string|max:40',
        // 🔥 Setia pada angka sakti Bapak: max:50, dengan pengecualian ID saat update
        'email'        => 'required|email|max:50|unique:users,email,'.$user->id,
        // 🔥 Username disesuaikan ke max:35, dengan pengecualian ID saat update
        'username'     => 'required|string|max:35|unique:users,username,'.$user->id,
        'role'         => 'required|in:tu,bendahara',
    ], [
        // --- PESAN ERROR KUSTOM ---
        'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
        'nama_lengkap.max'      => 'Nama lengkap maksimal 35 karakter.',
        
        'jk.required'  => 'Jenis kelamin wajib dipilih.',
        'jk.in'        => 'Jenis kelamin tidak valid.',
        
        'jabatan.required' => 'Jabatan wajib diisi.',
        'jabatan.max'      => 'Nama jabatan terlalu panjang (maksimal 40 karakter).',
        
        'email.required' => 'Email wajib diisi.',
        'email.email'  => 'Format email tidak valid.',
        'email.unique' => 'Email ini sudah digunakan oleh pengguna lain.',
        'email.max'    => 'Alamat email maksimal 50 karakter.',
        
        'username.required' => 'Username wajib diisi.',
        'username.unique'   => 'Username ini sudah dipakai, silakan gunakan yang lain.',
        'username.max'      => 'Username terlalu panjang (maksimal 35 karakter).',
        
        'role.required' => 'Hak akses (Role) wajib dipilih.',
        'role.in'       => 'Pilihan hak akses tidak valid.'
    ]);

        DB::beginTransaction();
        try {
            // 1. Update Akun Login
            $userData = [
                'name'     => $request->nama_lengkap,
                'email'    => $request->email,
                'username' => $request->username,
                'role'     => $request->role,
            ];
            // Jika password diisi, berarti mau ganti password. Jika kosong, biarkan password lama.
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            $user->update($userData);

            // 2. Update Profil Pegawai
            $pegawai->update([
                'nip'          => $request->nip,
                'nama_lengkap' => $request->nama_lengkap,
                'jk'           => $request->jk,
                'no_hp'        => $request->no_hp,
                'alamat'       => $request->alamat,
                'jabatan'      => $request->jabatan,
            ]);

            DB::commit();
            return redirect()->route('pegawai.index')->with('success', 'Data Pegawai berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $pegawai = Pegawai::findOrFail($id);
            $userId = $pegawai->user_id;
            
            $pegawai->delete(); // Hapus profil di tabel pegawais
            User::destroy($userId); // Hapus akun di tabel users

            DB::commit();
            return redirect()->route('pegawai.index')->with('success', 'Pegawai beserta hak aksesnya berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }
    public function show($id)
    {
        // Ambil data pegawai beserta akun user-nya
        $pegawai = \App\Models\Pegawai::with('user')->findOrFail($id);
        return view('admin.pegawai.show', compact('pegawai'));
    }

    // Fungsi edit, update, dan destroy akan kita buat selanjutnya!
}