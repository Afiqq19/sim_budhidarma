<?php

namespace App\Http\Controllers\WaliKelas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\WaliKelas;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfilController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // 🔥 PERBAIKAN: Ambil data wali kelas beserta relasi kelasnya
        $waliKelas = WaliKelas::where('user_id', $user->id)->with('kelas')->first();

        // 🔥 PENGAMAN: Jika data tidak ditemukan (nyasar pakai akun non-wali kelas), tolak aksesnya!
        if (!$waliKelas) {
            abort(403, 'Akses Ditolak: Anda tidak terdaftar sebagai Guru / Wali Kelas.');
        }

        // Variabel $pegawai dihapus karena ini halaman Wali Kelas
        return view('walikelas.profil.index', compact('user', 'waliKelas'));
    }

    public function edit()
    {
        $user = Auth::user();
        
        // Gunakan firstOrFail agar otomatis error 404 jika data tidak ada, bukannya error null
        $waliKelas = WaliKelas::where('user_id', $user->id)->firstOrFail();
        
        return view('walikelas.profil.edit', compact('user', 'waliKelas'));
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $waliKelas = WaliKelas::where('user_id', $user->id)->firstOrFail();

        // 🔥 UPDATE: Tambahkan validasi 'jk' (Jenis Kelamin)
        $request->validate([
            'email'  => 'required|email|unique:users,email,' . $user->id,
            'jk'     => 'required|in:L,P', 
            'no_hp'  => 'required|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        // Update data login (Tabel Users)
        $user->email = $request->email;
        $user->save(); 

        // 🔥 UPDATE: Simpan 'jk' ke profil wali_kelas
        $waliKelas->update([
            'jk'     => $request->jk,
            'no_hp'  => $request->no_hp,
            'alamat' => $request->alamat
        ]);

        return redirect()->route('walikelas.profil')->with('success', 'Profil berhasil diperbarui!');
    }

    public function editPassword()
    {
        return view('walikelas.profil.password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Password::defaults()],
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('walikelas.profil')->with('success', 'Password berhasil diubah!');
    }
}