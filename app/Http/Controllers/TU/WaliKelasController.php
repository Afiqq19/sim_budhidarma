<?php

namespace App\Http\Controllers\TU;

use App\Http\Controllers\Controller;
use App\Models\WaliKelas;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; 

class WaliKelasController extends Controller
{
    public function index()
    {
        $walikelas = WaliKelas::with(['user', 'kelas'])->latest()->get();
        $kelases = Kelas::orderBy('nama_kelas', 'asc')->get();
        
        return view('tu.walikelas.index', compact('walikelas', 'kelases'));
    }

    public function show($id)
    {
        $walikelas = WaliKelas::with(['user', 'kelas'])->findOrFail($id);
        return view('tu.walikelas.show', compact('walikelas'));
    }

    public function edit($id)
    {
        $walikelas = WaliKelas::with('user')->findOrFail($id);
        $kelases = Kelas::orderBy('nama_kelas', 'asc')->get();
        
        return view('tu.walikelas.edit', compact('walikelas', 'kelases'));
    }

    public function update(Request $request, $id)
    {
        $walikelas = WaliKelas::findOrFail($id);
        $user = User::findOrFail($walikelas->user_id);

        $request->validate([
            'nrg' => 'required|unique:wali_kelas,nrg,' . $id,
            'nip' => 'nullable|unique:wali_kelas,nip,' . $id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'nama_lengkap' => 'required',
            // kelas_id SEKARANG NULLABLE
            'kelas_id' => 'nullable|unique:wali_kelas,kelas_id,' . $id,
            'no_hp' => 'required',
            'jk' => 'required',
            // Opsional ganti password
            'password' => 'nullable|min:6', 
        ], [
            'nrg.unique' => 'NRG ini sudah terdaftar!',
            'nip.unique' => 'NIP ini sudah terdaftar!',
            'kelas_id.unique' => 'Kelas ini sudah memiliki Wali Kelas! Silakan pilih kelas lain.',
            'email.unique' => 'Email sudah digunakan oleh pengguna lain.',
            'password.min' => 'Password baru minimal 6 karakter.',
        ]);

        DB::transaction(function () use ($request, $walikelas, $user) {
            // 1. Update Akun Login
            $userData = [
                'name' => $request->nama_lengkap,
                'email' => $request->email,
                'username' => $request->nrg, // Update username dari form edit
            ];

            // Jika form password diisi, ikut di-update
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);

            // 2. Update Biodata Wali Kelas
            $walikelas->update([
                'kelas_id' => $request->kelas_id, // Bisa NULL jika admin memilih "Tidak Ada"
                'nrg' => $request->nrg,
                'nip' => $request->nip,
                'nama_lengkap' => $request->nama_lengkap,
                'jk' => $request->jk,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
            ]);
        });

        return redirect()->route('tu.walikelas.index')->with('success', 'Data Wali Kelas berhasil diperbarui!');
    }

}