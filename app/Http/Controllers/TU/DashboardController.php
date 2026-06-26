<?php

namespace App\Http\Controllers\TU;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Walikelas;
use App\Models\Kelas;
use App\Models\Jurusan;
use App\Models\TahunAjaran; // 🔥 Tambahkan ini agar tidak error

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil Tahun Ajaran Aktif
        $tahunAktif = TahunAjaran::where('is_active', 1)->first();

        // 1. Hitung Statistik Master Data
        $total_siswa = Siswa::where('status_siswa', 'Aktif')->count();
        $total_alumni = Siswa::where('status_siswa', 'Alumni')->count(); 
        $total_pindah = Siswa::where('status_siswa', 'Pindah')->count(); 
        
        $total_walikelas = Walikelas::count();
        $total_kelas = Kelas::count();
        $total_jurusan = Jurusan::count();

        // 2. Ambil 5 Data Siswa Terakhir yang Baru Didaftarkan
        $siswa_baru = Siswa::with('kelas')
                           ->where('status_siswa', 'Aktif')
                           ->latest()
                           ->take(5)
                           ->get();

        // 🔥 PERBAIKAN: Arahkan ke tu.dashboard, BUKAN admin.dashboard 🔥
        return view('tu.dashboard', compact(
            'tahunAktif', // Kirim tahun aktif ke view
            'total_siswa', 'total_alumni', 'total_pindah',
            'total_walikelas', 'total_kelas', 'total_jurusan',
            'siswa_baru'
        ));
    }
}