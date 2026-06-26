<?php

namespace App\Http\Controllers\TU;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;

class AlumniController extends Controller
{
    public function index()
    {
        // Ambil data yang statusnya Alumni ATAU Pindah
        $alumnis = Siswa::whereIn('status_siswa', ['Alumni', 'Pindah'])
                        ->orderBy('tahun_lulus', 'desc')
                        ->orderBy('nama_lengkap', 'asc')
                        ->get();

        return view('tu.alumni.index', compact('alumnis'));
    }

    // TAMBAHKAN FUNGSI INI
    public function show($id)
    {
        // Ambil data alumni beserta relasi kelas dan user-nya
        $alumni = Siswa::with(['kelas', 'user'])->findOrFail($id);
        
        return view('tu.alumni.show', compact('alumni'));
    }
}