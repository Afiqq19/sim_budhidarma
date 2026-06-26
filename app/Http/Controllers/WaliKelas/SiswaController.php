<?php

namespace App\Http\Controllers\WaliKelas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\WaliKelas;
use Illuminate\Support\Facades\Auth;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        // Cari data Wali Kelas berdasarkan user yang login
        $waliKelas = WaliKelas::where('user_id', $user->id)->first();

        if (!$waliKelas) {
            return redirect()->route('walikelas.dashboard')->with('error', 'Akses ditolak.');
        }

        // Ambil data siswa berdasarkan kelas yang dipegang wali kelas
        $query = Siswa::where('kelas_id', $waliKelas->kelas_id)->where('status_siswa', 'Aktif');

        // Fitur Pencarian Nama/NISN
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%$search%")
                  ->orWhere('nisn', 'like', "%$search%");
            });
        }

        $siswas = $query->orderBy('nama_lengkap', 'asc')->get();

        return view('walikelas.siswa.index', compact('siswas', 'waliKelas'));
    }

    public function show($id)
    {
        $user = Auth::user();
        $waliKelas = WaliKelas::where('user_id', $user->id)->first();
        
        // Cari siswa dan pastikan dia memang murid di kelas wali kelas tersebut (Keamanan)
        $siswa = Siswa::where('id', $id)->where('kelas_id', $waliKelas->kelas_id)->firstOrFail();

        return view('walikelas.siswa.show', compact('siswa'));
    }
}