<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Pegawai;
use App\Models\User;

class ProfileController extends Controller
{
    public function edit()
    {
        // Mencari user langsung dari Model agar aman dari garis merah VS Code
        $user = User::find(Auth::id());
        $pegawai = $user->pegawai;

        // 💡 Catatan: Sesuaikan 'bendahara.profile.index' jika Bapak menaruh file-nya dengan nama index.blade.php
        // Ganti kata 'index' menjadi 'edit'
        return view('bendahara.profile.edit', compact('user', 'pegawai'));
    }

    public function update(Request $request)
    {
        $user = User::find(Auth::id());

        // 1. Validasi Inputan Gabungan (Tabel Users & Pegawais)
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed', // Sesuai standarisasi minimal 6 karakter
            'nip' => 'nullable|string|max:50',
            'nama_lengkap' => 'required|string|max:255',
            'jk' => 'required|in:L,P',                 // Validasi Jenis Kelamin wajib L/P
            'no_hp' => 'required|string|max:20',       // Validasi No HP wajib diisi
            'alamat' => 'nullable|string',             // Validasi Alamat opsional
        ], [
            'username.unique' => 'Username ini sudah dipakai oleh pengguna lain.',
            'email.unique' => 'Email ini sudah dipakai oleh pengguna lain.',
            'password.min' => 'Password baru minimal harus 6 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok!'
        ]);

        // 2. Update Akun Login (Tabel Users)
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        
        $user->save(); 

        // 3. Update atau Create Biodata Lengkap (Tabel Pegawais)
        if ($user->pegawai) {
            // Jika data pegawai sudah ada, kita update semua field barunya
            $user->pegawai->update([
                'nip' => $request->nip,
                'nama_lengkap' => $request->nama_lengkap,
                'jk' => $request->jk,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
            ]);
        } else {
            // Jaga-jaga kalau akun belum punya relasi pegawai, langsung buatkan baru
            Pegawai::create([
                'user_id' => $user->id,
                'nip' => $request->nip,
                'nama_lengkap' => $request->nama_lengkap,
                'jk' => $request->jk,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'jabatan' => 'Bendahara'
            ]);
        }

        return redirect()->back()->with('success', 'Profil dan Akun Anda berhasil diperbarui!');
    }
}