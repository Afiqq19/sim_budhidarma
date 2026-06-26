<?php

namespace App\Http\Controllers\WaliKelas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Nilai;
use App\Models\TahunAjaran;
use App\Models\Mapel;
use App\Models\WaliKelas;
use Illuminate\Support\Facades\Auth;

class NilaiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $waliKelas = WaliKelas::where('user_id', $user->id)->first();

        if (!$waliKelas) {
            return redirect()->route('walikelas.dashboard')->with('error', 'Akses ditolak.');
        }

        $kelas = $waliKelas->kelas; 
        $tahunAktif = TahunAjaran::where('is_active', 1)->first();
        
        // 🔥 PERBAIKAN UTAMA: Cek apakah guru sudah punya kelas!
        if (!$kelas) {
            // Jika belum punya kelas, lempar data kosong (mencegah error 500)
            $listMapel = collect();
            $siswas = collect();
            $selectedMapelId = null;
        } else {
            // Jika sudah punya kelas, jalankan logika normal
            $listMapel = $kelas->mapels()->orderBy('kelompok', 'asc')->orderBy('nama_mapel', 'asc')->get();
            $selectedMapelId = $request->mapel_id ?? ($listMapel->first()->id ?? null);

            $siswas = Siswa::where('kelas_id', $waliKelas->kelas_id)
                            ->with(['nilais' => function($q) use ($tahunAktif, $selectedMapelId) {
                                $q->where('tahun_ajaran_id', $tahunAktif->id)
                                  ->where('mapel_id', $selectedMapelId);
                            }])
                            ->orderBy('nama_lengkap', 'asc')
                            ->get();
        }

        return view('walikelas.nilai.index', compact(
            'siswas', 
            'waliKelas', 
            'kelas', 
            'tahunAktif', 
            'listMapel', 
            'selectedMapelId'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mapel_id' => 'required',
            'nilai' => 'required|array'
        ]);

        $tahunAktif = TahunAjaran::where('is_active', 1)->first();

        foreach ($request->nilai as $siswa_id => $v) {
            // 1. Ambil nilai dari form, jika form kosong string ('') jadikan null agar tidak error
            $nP = isset($v['nilai_pengetahuan']) && $v['nilai_pengetahuan'] !== '' ? $v['nilai_pengetahuan'] : null;
            $nK = isset($v['nilai_keterampilan']) && $v['nilai_keterampilan'] !== '' ? $v['nilai_keterampilan'] : null;
            
            // 2. Ambil nilai akhir. Defaultkan ke 0, BUKAN null!
            $nA = isset($v['nilai_akhir']) && $v['nilai_akhir'] !== '' ? $v['nilai_akhir'] : 0;

            // 3. LOGIKA CERDAS: Jika nilai akhir di form 0/kosong, tapi Pengetahuan & Keterampilan diisi, otomatis hitung rata-ratanya!
            if ($nA == 0 && $nP !== null && $nK !== null) {
                $nA = ($nP + $nK) / 2;
            }

            Nilai::updateOrCreate(
                [
                    'siswa_id' => $siswa_id,
                    'tahun_ajaran_id' => $tahunAktif->id,
                    'mapel_id' => $request->mapel_id
                ],
                [
                    'nilai_pengetahuan' => $nP,
                    'nilai_keterampilan' => $nK,
                    'nilai_akhir' => $nA, // Sekarang pasti selalu ada angkanya (Anti Error 1048)
                    'catatan_wali_kelas' => $v['catatan'] ?? null,
                ]
            );
        }

        return redirect()->back()->with('success', 'Nilai berhasil disimpan!');
    }

   public function rekap()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $waliKelas = \App\Models\WaliKelas::where('user_id', $user->id)->with('kelas')->first();
        
        if (!$waliKelas) {
            return redirect()->route('walikelas.dashboard')->with('error', 'Akses ditolak.');
        }

        // 🔥 FOKUS HANYA PADA TAHUN AJARAN AKTIF 🔥
        $tahunAktif = \App\Models\TahunAjaran::where('is_active', 1)->first();

        // PENGAMAN JIKA GURU BELUM PUNYA KELAS
        if (!$waliKelas->kelas_id) {
            $siswas = collect();
        } else {
            // Tarik data siswa HANYA di tahun aktif
            $siswas = \App\Models\Siswa::where('kelas_id', $waliKelas->kelas_id)
                ->with(['nilais' => function($q) use ($tahunAktif) {
                    if ($tahunAktif) {
                        $q->where('tahun_ajaran_id', $tahunAktif->id);
                    }
                }])->get();

            // LOGIKA PERANGKINGAN
            $rankingData = [];
            foreach($siswas as $s) {
                $rankingData[$s->id] = $s->nilais->sum('nilai_akhir');
            }
            
            arsort($rankingData); 

            $rank = 1;
            foreach($rankingData as $id => $total) {
                $siswa = $siswas->where('id', $id)->first();
                $siswa->peringkat = $total > 0 ? $rank++ : '-'; 
                $siswa->total_nilai = $total;
                $siswa->jumlah_mapel = $siswa->nilais->count(); 
            }

            $siswas = $siswas->sortBy('nama_lengkap');
        }

        return view('walikelas.nilai.rekap', compact('siswas', 'waliKelas', 'tahunAktif'));
    }

    public function detail($id)
    {
        $tahunAktif = TahunAjaran::where('is_active', 1)->first();
        $siswa = Siswa::with('kelas')->findOrFail($id);

        $nilais = Nilai::where('siswa_id', $id)
            ->where('tahun_ajaran_id', $tahunAktif->id)
            ->with('mapel')
            ->get();

        $nilaiGrouped = $nilais->groupBy(function($item) {
            return $item->mapel->kelompok;
        });

        return view('walikelas.nilai.detail', compact('siswa', 'tahunAktif', 'nilaiGrouped'));
    }
}