<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $siswa = Siswa::where('user_id', $user->id)->with('kelas')->first();
        return view('siswa.profil.index', compact('siswa', 'user'));
    }

    public function edit()
    {
        $siswa = Siswa::where('user_id', Auth::id())->first();
        return view('siswa.profil.edit', compact('siswa'));
    }

    /**
     * PROSES UPDATE PROFIL (ANTI ERROR MASS ASSIGNMENT)
     */
    public function update(Request $request)
    {
        $siswa = Siswa::where('user_id', Auth::id())->first();
        
        // 1. Tambahkan validasi untuk field yang baru dibuka kuncinya
        $request->validate([
            'jk' => 'required|in:L,P',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'no_hp_siswa' => 'required|string|max:20',
            'alamat' => 'required|string',
            'nama_orang_tua' => 'nullable|string|max:255',
            'no_hp_ortu' => 'required|string|max:20',
        ]);

        // 2. Menggunakan cara manual property assignment agar kebal error $fillable
        $siswa->jk = $request->jk;                             // <-- Tambahan Baru
        $siswa->tempat_lahir = $request->tempat_lahir;         // <-- Tambahan Baru
        $siswa->tanggal_lahir = $request->tanggal_lahir;       // <-- Tambahan Baru
        $siswa->no_hp_siswa = $request->no_hp_siswa;
        $siswa->alamat = $request->alamat;
        $siswa->nama_orang_tua = $request->nama_orang_tua;
        $siswa->no_hp_ortu = $request->no_hp_ortu;
        $siswa->save(); 

        return redirect()->route('siswa.profil')->with('success', 'Profil berhasil diperbarui!');
    }

    public function password()
    {
        return view('siswa.profil.password');
    }

    /**
     * PROSES GANTI PASSWORD (PASTI BERHASIL)
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ], [
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'new_password.min' => 'Password baru minimal 8 karakter.'
        ]);

        $user = User::find(Auth::id());

        // Verifikasi apakah password lama benar
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama yang Anda masukkan salah.']);
        }

        // Simpan password baru menggunakan Hash
        $user->password = Hash::make($request->new_password);
        $user->save(); 

        return redirect()->route('siswa.profil')->with('success', 'Password akun Anda berhasil diganti!');
    }
}