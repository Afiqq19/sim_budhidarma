<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    public function index()
    {
        // 🔥 PERBAIKAN: Ambil langsung dari Model User berdasarkan ID yang login
        $user = \App\Models\User::find(Auth::id());
        
        return view('admin.profil.profile', compact('user'));
    }

    public function update(Request $request)
    {
        // 🔥 PERBAIKAN: Ambil langsung dari Model User agar fungsi update() dikenali
        $user = \App\Models\User::find(Auth::id());

        // Validasi inputan
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'nullable|email|unique:users,email,' . $user->id,
            'username' => 'required|string|unique:users,username,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
        ], [
            'name.required'     => 'Nama lengkap wajib diisi.',
            'username.required' => 'Username login wajib diisi.',
            'username.unique'   => 'Username ini sudah dipakai akun lain, cari yang lain Pak.',
            'email.unique'      => 'Email ini sudah dipakai akun lain.',
            'password.min'      => 'Password baru minimal 6 karakter Pak.',
            'password.confirmed'=> 'Konfirmasi password baru tidak cocok, silakan cek kembali.',
        ]);

        // Siapkan data yang mau diupdate
        $updateData = [
            'name'     => $request->name,
            'email'    => $request->email,
            'username' => $request->username,
        ];

        // Jika password diisi, baru kita enkripsi
        if ($request->filled('password')) {
            $updateData['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        // Sekarang error garis merahnya pasti hilang!
        $user->update($updateData);

        return redirect()->route('admin.profile')->with('success', 'Profil Admin berhasil diperbarui!');
    }
}