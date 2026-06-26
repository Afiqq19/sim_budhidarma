<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use App\Models\WaliKelas;
use App\Models\Siswa;
use App\Models\TagihanSpp;
use App\Models\Pembayaran;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. STATISTIK SDM
        $totalPegawai = Pegawai::count();
        $totalWaliKelas = WaliKelas::count();
        $totalSiswa = Siswa::where('status_siswa', 'Aktif')->count();

        // 2. STATISTIK KEUANGAN BULAN INI
        $bulanSekarang = now()->translatedFormat('F'); // Contoh: April
        
        // Cek apakah ada Tahun Ajaran yang aktif
        $tahunAktif = TahunAjaran::where('is_active', 1)->first();
        
        // Variabel default (Jika belum ada tahun ajaran aktif)
        $uangMasukBulanIni = 0;
        $sudahBayarCount = 0;
        $belumBayarCount = 0;

        // 🔥 LOGIKA ANTI ERROR: Jalankan ini HANYA JIKA ada tahun ajaran yang aktif 🔥
        if ($tahunAktif) {
            $idsTahunIni = TahunAjaran::where('tahun', $tahunAktif->tahun)->pluck('id');

            $uangMasukBulanIni = Pembayaran::whereMonth('tanggal_bayar', now()->month)
                ->whereYear('tanggal_bayar', now()->year)
                ->where('status_bayar', 'success')
                ->sum('jumlah_bayar');

            $sudahBayarCount = TagihanSpp::whereIn('tahun_ajaran_id', $idsTahunIni)
                ->where('bulan', $bulanSekarang)
                ->where('status', 'Lunas')
                ->count();

            $belumBayarCount = TagihanSpp::whereIn('tahun_ajaran_id', $idsTahunIni)
                ->where('bulan', $bulanSekarang)
                ->where('status', 'Belum Lunas')
                ->count();
        }

        // List 5 siswa terakhir yang baru saja bayar
        $transaksiTerakhir = Pembayaran::with('siswa')
            ->where('status_bayar', 'success')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalPegawai', 'totalWaliKelas', 'totalSiswa',
            'bulanSekarang', 'uangMasukBulanIni', 'sudahBayarCount', 'belumBayarCount',
            'transaksiTerakhir', 'tahunAktif'
        ));
    }
}