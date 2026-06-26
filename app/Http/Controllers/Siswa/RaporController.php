<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Nilai;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RaporController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $siswa = Siswa::where('user_id', $user->id)->with('kelas')->first();
        $tahunAktifObj = TahunAjaran::where('is_active', 1)->first();

        // 🔥 1. LOGIKA BARU: FILTER TAHUN AJARAN BERDASARKAN STATUS SISWA 🔥
        if ($siswa->status_siswa == 'Alumni' || $siswa->status_siswa == 'Pindah') {
            // Jika Alumni/Pindah: HANYA tampilkan Tahun Ajaran di mana dia punya jejak nilai
            $listTahunAjaran = TahunAjaran::whereHas('nilais', function($q) use ($siswa) {
                $q->where('siswa_id', $siswa->id);
            })->orderBy('tahun', 'desc')->orderBy('semester', 'desc')->get();
            
            // Default yang dilihat adalah tahun terakhir dia sekolah (tahun paling atas di list)
            $selectedTahunId = $request->get('tahun_ajaran_id', $listTahunAjaran->first()->id ?? null);
            
        } else {
            // Jika Siswa Aktif: Tampilkan tahun di mana dia punya nilai + tahun aktif saat ini
            $listTahunAjaran = TahunAjaran::where(function($query) use ($siswa) {
                $query->whereHas('nilais', function($q) use ($siswa) {
                    $q->where('siswa_id', $siswa->id);
                })->orWhere('is_active', 1);
            })->orderBy('tahun', 'desc')->orderBy('semester', 'desc')->get();
            
            // Default yang dilihat adalah tahun aktif (karena dia masih sekolah)
            $selectedTahunId = $request->get('tahun_ajaran_id', $tahunAktifObj->id ?? ($listTahunAjaran->first()->id ?? null));
        }

        $tahunDilihat = TahunAjaran::find($selectedTahunId);

        // 🔥 PENGAMAN: Jika tidak ada tahun ajaran yang ditemukan sama sekali 🔥
        if (!$tahunDilihat) {
            $nilaiGrouped = collect();
            $totalNilai = 0;
            $rataRata = 0;
            $jumlahMapel = 0;
            $myRank = '-';
            $totalSiswa = 0;
            return view('siswa.rapor.index', compact(
                'siswa', 'tahunAktifObj', 'listTahunAjaran', 'tahunDilihat', 'selectedTahunId',
                'nilaiGrouped', 'totalNilai', 'rataRata', 'jumlahMapel', 'myRank', 'totalSiswa'
            ));
        }

        // 2. AMBIL DATA NILAI & GROUPING KELOMPOK (A, B, C, C2, C3)
        $nilais = Nilai::where('siswa_id', $siswa->id)
            ->where('tahun_ajaran_id', $selectedTahunId)
            ->with('mapel')
            ->get();

        $nilaiGrouped = $nilais->groupBy(function($item) {
            return $item->mapel->kelompok ?? 'Lainnya'; 
        });

        // 3. HITUNG STATISTIK 
        $totalNilai = $nilais->sum('nilai_akhir');
        $jumlahMapel = $nilais->count();
        $rataRata = $jumlahMapel > 0 ? round($totalNilai / $jumlahMapel, 2) : 0;

        // 4. LOGIKA PERINGKAT KELAS
        $semuaSiswaDiKelas = Siswa::where('kelas_id', $siswa->kelas_id)
            ->with(['nilais' => function($q) use ($selectedTahunId) {
                $q->where('tahun_ajaran_id', $selectedTahunId);
            }])->get();

        $rankingData = [];
        foreach($semuaSiswaDiKelas as $s) {
            $rankingData[$s->id] = $s->nilais->sum('nilai_akhir');
        }
        arsort($rankingData);

        $myRank = '-'; 
        $rank = 1;
        $totalSiswa = count($rankingData);
        
        // Peringkat hanya dihitung jika siswa punya nilai di semester tsb
        if ($jumlahMapel > 0) {
            foreach($rankingData as $id => $total) {
                if($id == $siswa->id) { $myRank = $rank; break; }
                $rank++;
            }
        }

        return view('siswa.rapor.index', compact(
            'siswa', 'tahunAktifObj', 'listTahunAjaran', 'tahunDilihat', 'selectedTahunId',
            'nilaiGrouped', 'totalNilai', 'rataRata', 'jumlahMapel', 'myRank', 'totalSiswa'
        ));
    }
}