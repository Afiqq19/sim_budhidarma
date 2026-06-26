<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\TagihanSpp;
use Carbon\Carbon;

class DashboardController extends Controller
{
   public function index()
    {
        $hariIni = \Carbon\Carbon::today();
        $bulanIni = \Carbon\Carbon::now()->format('m');
        $tahunIni = \Carbon\Carbon::now()->format('Y');

        // 1. Hitung Pemasukan (Rupiah)
        $pemasukanHariIni = Pembayaran::where('status_bayar', '!=', 'failed')->whereDate('tanggal_bayar', $hariIni)->sum('jumlah_bayar');
        $pemasukanBulanIni = Pembayaran::where('status_bayar', '!=', 'failed')->whereMonth('tanggal_bayar', $bulanIni)->whereYear('tanggal_bayar', $tahunIni)->sum('jumlah_bayar');

        // 2. Hitung Potensi Tunggakan (Rupiah)
        $urutanBulan = [
            'Juli' => 1, 'Agustus' => 2, 'September' => 3, 'Oktober' => 4, 'November' => 5, 'Desember' => 6,
            'Januari' => 7, 'Februari' => 8, 'Maret' => 9, 'April' => 10, 'Mei' => 11, 'Juni' => 12
        ];
        \Carbon\Carbon::setLocale('id');
        $bulanSekarangStr = \Carbon\Carbon::now()->translatedFormat('F');
        $indexBatas = $urutanBulan[$bulanSekarangStr] ?? 12;
        $bulanJatuhTempo = array_keys(array_filter($urutanBulan, function($val) use ($indexBatas) { return $val <= $indexBatas; }));
        
        $totalTunggakan = TagihanSpp::where('status', 'Belum Lunas')->whereIn('bulan', $bulanJatuhTempo)->sum('nominal');

        // 3. BARU: Hitung Progress Siswa Lunas Bulan Ini
        $totalSiswaAktif = \App\Models\Siswa::where('status_siswa', 'Aktif')->count();
        $siswaLunasBulanIni = 0;
        $tahunAktif = \App\Models\TahunAjaran::where('is_active', 1)->first();
        if ($tahunAktif) {
            $siswaLunasBulanIni = TagihanSpp::where('tahun_ajaran_id', $tahunAktif->id)
                                            ->where('bulan', $bulanSekarangStr)
                                            ->where('status', 'Lunas')
                                            ->count();
        }

        // 4. Ambil 5 Transaksi Paling Baru
        $transaksiTerbaru = Pembayaran::with(['siswa.kelas', 'tagihan_spp'])
                                      ->where('status_bayar', '!=', 'failed')
                                      ->orderBy('created_at', 'desc')
                                      ->take(5)
                                      ->get();

        return view('bendahara.dashboard', compact(
            'pemasukanHariIni', 'pemasukanBulanIni', 'totalTunggakan', 
            'transaksiTerbaru', 'totalSiswaAktif', 'siswaLunasBulanIni', 'bulanSekarangStr'
        ));
    }
}