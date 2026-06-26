<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Nilai;
use App\Models\TagihanSpp;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $siswa = Siswa::where('user_id', $user->id)->with('kelas')->first();
        
        // Cek Tahun Ajaran Aktif
        $tahunAktif = TahunAjaran::where('is_active', 1)->first();

        // Siapkan variabel default (Kosong) jika Tahun Ajaran belum diatur
        $jumlahMapel = 0;
        $myRank = '-';
        $totalNilai = 0;
        $statusSppBulanIni = 'Belum Diatur';
        $totalBulanNunggak = 0;

        // 🔥 PERBAIKAN: Mapping Array Bulan agar PASTI ejaan Indonesia (Mencegah Bug 'June' vs 'Juni') 🔥
        $daftarBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        // Ambil angka bulan saat ini (contoh: 6), lalu ubah jadi kata 'Juni'
        $bulanSekarang = $daftarBulan[(int)now()->format('n')]; 

        // 🔥 LOGIKA ANTI ERROR: Jalankan hanya jika ada Tahun Ajaran yang Aktif 🔥
        if ($tahunAktif) {
            
            // 1. CEK JUMLAH MAPEL & TOTAL NILAI
            $jumlahMapel = Nilai::where('siswa_id', $siswa->id)
                ->where('tahun_ajaran_id', $tahunAktif->id)
                ->count();

            // 2. LOGIKA RANKING (Hanya berjalan jika nilai sudah diisi minimal 1 mapel)
            if ($jumlahMapel > 0) {
                $semuaSiswa = Siswa::where('kelas_id', $siswa->kelas_id)
                    ->with(['nilais' => function($q) use ($tahunAktif) {
                        $q->where('tahun_ajaran_id', $tahunAktif->id);
                    }])->get();

                $rankingData = [];
                foreach($semuaSiswa as $s) {
                    $rankingData[$s->id] = $s->nilais->sum('nilai_akhir');
                }
                arsort($rankingData);

                $totalNilai = $rankingData[$siswa->id] ?? 0;

                $rank = 1;
                foreach($rankingData as $id => $total) {
                    if($id == $siswa->id) {
                        $myRank = $rank;
                        break;
                    }
                    $rank++;
                }
            }

            // 3. STATUS KEUANGAN (Fokus ke Bulan Berjalan)
            $idsTahunIni = TahunAjaran::where('tahun', $tahunAktif->tahun)->pluck('id');

            // Karena $bulanSekarang sekarang PASTI 'Juni', pencarian ke database akan berhasil
            $tagihanBulanIni = TagihanSpp::where('siswa_id', $siswa->id)
                ->whereIn('tahun_ajaran_id', $idsTahunIni) 
                ->where('bulan', $bulanSekarang)
                ->first();

            $statusSppBulanIni = $tagihanBulanIni ? $tagihanBulanIni->status : 'Belum Dibuat';

            $urutanBulan = ['Juli'=>1, 'Agustus'=>2, 'September'=>3, 'Oktober'=>4, 'November'=>5, 'Desember'=>6, 'Januari'=>7, 'Februari'=>8, 'Maret'=>9, 'April'=>10, 'Mei'=>11, 'Juni'=>12];
            $indexSekarang = $urutanBulan[$bulanSekarang] ?? 10; 

            $totalBulanNunggak = TagihanSpp::where('siswa_id', $siswa->id)
                ->whereIn('tahun_ajaran_id', $idsTahunIni) 
                ->where('status', 'Belum Lunas')
                ->get()
                ->filter(function($t) use ($urutanBulan, $indexSekarang) {
                    return ($urutanBulan[$t->bulan] ?? 0) <= $indexSekarang;
                })->count();
        }

        return view('siswa.dashboard', compact(
            'siswa', 'tahunAktif', 'myRank', 'statusSppBulanIni', 'bulanSekarang', 'totalBulanNunggak', 'totalNilai', 'jumlahMapel'
        ));
    }
}