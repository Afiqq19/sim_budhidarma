<?php

namespace App\Http\Controllers\TU;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Pegawai; // <-- Jangan lupa panggil model Pegawai

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // Ambil data detail pegawai berdasarkan user_id
        $pegawai = Pegawai::where('user_id', $user->id)->first();

        // 🌟 LOGIKA AVATAR OTOMATIS BERDASARKAN JENIS KELAMIN 🌟
        $avatar = 'images/username_lk.png'; // Default jika belum diset (Laki-laki)
        
        if ($pegawai && $pegawai->jk == 'P') {
            $avatar = 'images/username_pr.png'; // Avatar Perempuan
        } elseif ($pegawai && $pegawai->jk == 'L') {
            $avatar = 'images/username_lk.png'; // Avatar Laki-laki
        }

        return view('tu.profile.index', compact('user', 'pegawai', 'avatar'));
    }

    public function update(Request $request)
    {
        $user = User::find(Auth::id());

        // Validasi input gabungan (Users & Pegawais)
        $request->validate([
            // Validasi tabel users
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed', 
            // Validasi tabel pegawais
            'nip' => 'nullable|string|max:50',
            'nama_lengkap' => 'required|string|max:255',
            'jk' => 'required|in:L,P',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'nullable|string',
        ], [
            'username.unique' => 'Username ini sudah dipakai oleh pengguna lain.',
            'email.unique' => 'Email ini sudah dipakai oleh pengguna lain.',
            'password.min' => 'Password minimal harus 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok!'
        ]);

        // ==========================================
        // 1. UPDATE DATA AKUN (Tabel Users)
        // ==========================================
        $user->name = $request->name; // Diambil otomatis dari input nama_lengkap
        $user->username = $request->username; 
        $user->email = $request->email; 

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        // ==========================================
        // 2. UPDATE DATA BIODATA (Tabel Pegawais)
        // ==========================================
        $pegawai = Pegawai::where('user_id', $user->id)->first();
        
        if ($pegawai) {
            // Jika data pegawai sudah ada, langsung update
            $pegawai->update([
                'nip' => $request->nip,
                'nama_lengkap' => $request->nama_lengkap,
                'jk' => $request->jk,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
            ]);
        } else {
            // Jika belum ada, buat data pegawai baru
            Pegawai::create([
                'user_id' => $user->id,
                'nip' => $request->nip,
                'nama_lengkap' => $request->nama_lengkap,
                'jk' => $request->jk,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'jabatan' => 'Staf Tata Usaha', // Default jabatan untuk TU
            ]);
        }

        return redirect()->back()->with('success', 'Data Profil & Biodata Pegawai berhasil diperbarui!');
    }
}