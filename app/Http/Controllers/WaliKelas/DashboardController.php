<?php

namespace App\Http\Controllers\WaliKelas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\TagihanSpp;
use App\Models\TahunAjaran;
use App\Models\Pembayaran;
use App\Models\WaliKelas;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Cari ke tabel wali_kelas berdasarkan user_id yang sedang login
        $waliKelas = WaliKelas::where('user_id', $user->id)->with('kelas')->first();

        // JIKA BUKAN WALI KELAS (Atau data tidak ditemukan)
        if (!$waliKelas) {
            return view('walikelas.dashboard', ['waliKelas' => null]);
        }

        // Ambil data kelasnya
        $kelas = $waliKelas->kelas;

        Carbon::setLocale('id');
        $bulanSekarang = Carbon::now()->translatedFormat('F');
        $tahunAktif = TahunAjaran::where('is_active', 1)->first();

        // 🔥 SET NILAI DEFAULT (Jika guru belum punya kelas) 🔥
        $totalSiswa = 0;
        $siswaLunasBulanIni = 0;
        $pembayaranTerbaru = collect(); // Bikin data array kosong agar tidak error di view

        // 🔥 SABUK PENGAMAN UTAMA 🔥
        // Hanya hitung data siswa dan uang jika guru tersebut SUDAH DITUGASKAN ke sebuah kelas
        if ($kelas) {
            
            // 1. Hitung total siswa di kelas tersebut
            $totalSiswa = Siswa::where('kelas_id', $kelas->id)->where('status_siswa', 'Aktif')->count();

            // 2. Hitung siswa yang sudah lunas bulan ini
            if ($tahunAktif) {
                $siswaLunasBulanIni = TagihanSpp::whereHas('siswa', function($q) use ($kelas) {
                    $q->where('kelas_id', $kelas->id);
                })
                ->where('tahun_ajaran_id', $tahunAktif->id)
                ->where('bulan', $bulanSekarang)
                ->where('status', 'Lunas')
                ->count();
            }

            // 3. Ambil 5 riwayat pembayaran terbaru dari siswa di kelas tersebut
            $pembayaranTerbaru = Pembayaran::with(['siswa', 'tagihan_spp'])
                ->whereHas('siswa', function($q) use ($kelas) {
                    $q->where('kelas_id', $kelas->id);
                })
                ->where('status_bayar', '!=', 'failed')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        }

        return view('walikelas.dashboard', compact(
            'waliKelas', 'kelas', 'totalSiswa', 'siswaLunasBulanIni', 'bulanSekarang', 'pembayaranTerbaru'
        ));
    }
}