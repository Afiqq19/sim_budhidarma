<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TagihanSpp;
use App\Models\TahunAjaran;
use Carbon\Carbon;

class LaporanTunggakanController extends Controller
{
    public function index(Request $request)
    {
        $listTahun = TahunAjaran::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');
        $listKelas = \App\Models\Kelas::orderBy('nama_kelas', 'asc')->get();

        // 1. Urutan Bulan Akademik
        $urutanBulan = [
            'Juli' => 1, 'Agustus' => 2, 'September' => 3, 'Oktober' => 4, 'November' => 5, 'Desember' => 6,
            'Januari' => 7, 'Februari' => 8, 'Maret' => 9, 'April' => 10, 'Mei' => 11, 'Juni' => 12
        ];

        // 2. OTOMATIS DETEKSI BULAN SEKARANG
        Carbon::setLocale('id');
        $bulanSekarang = Carbon::now()->translatedFormat('F'); // Langsung baca kalender server (contoh: April)
        $indexBatas = $urutanBulan[$bulanSekarang] ?? 12;

        // Kumpulkan bulan-bulan yang sudah lewat/sedang berjalan saja
        $bulanJatuhTempo = array_keys(array_filter($urutanBulan, function($val) use ($indexBatas) {
            return $val <= $indexBatas;
        }));

        // 3. Query Tagihan (Otomatis potong sampai bulan ini saja)
        $query = TagihanSpp::with(['siswa.kelas', 'tahun_ajaran'])
            ->where('status', 'Belum Lunas')
            ->whereIn('bulan', $bulanJatuhTempo); 

        // 4. Filter Tahun Ajaran Gabungan
        if ($request->has('tahun_filter') && $request->tahun_filter != '') {
            $idsTahun = TahunAjaran::where('tahun', $request->tahun_filter)->pluck('id');
            $query->whereIn('tahun_ajaran_id', $idsTahun);
        } else {
            $tahunAktif = TahunAjaran::where('is_active', 1)->first();
            if ($tahunAktif) {
                $idsTahun = TahunAjaran::where('tahun', $tahunAktif->tahun)->pluck('id');
                $query->whereIn('tahun_ajaran_id', $idsTahun);
            }
        }

        // 5. Filter Kelas & Pencarian
        if ($request->kelas_filter) {
            $query->whereHas('siswa', function($q) use ($request) { $q->where('kelas_id', $request->kelas_filter); });
        }
        if ($request->search) {
            $search = $request->search;
            $query->whereHas('siswa', function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%$search%")->orWhere('nisn', 'like', "%$search%");
            });
        }

        $semuaTunggakan = $query->get();
        $tunggakanPerSiswa = $semuaTunggakan->groupBy('siswa_id');
        $totalPotensiUang = $semuaTunggakan->sum('nominal');

        return view('bendahara.laporan.tunggakan', compact(
            'tunggakanPerSiswa', 'listTahun', 'listKelas', 'totalPotensiUang', 'bulanSekarang'
        ));
    }

    public function exportExcel(Request $request)
    {
        // Samakan logikanya dengan index agar Excelnya juga otomatis
        $urutanBulan = [
            'Juli' => 1, 'Agustus' => 2, 'September' => 3, 'Oktober' => 4, 'November' => 5, 'Desember' => 6,
            'Januari' => 7, 'Februari' => 8, 'Maret' => 9, 'April' => 10, 'Mei' => 11, 'Juni' => 12
        ];
        
        Carbon::setLocale('id');
        $indexBatas = $urutanBulan[Carbon::now()->translatedFormat('F')] ?? 12;
        $bulanJatuhTempo = array_keys(array_filter($urutanBulan, function($val) use ($indexBatas) { return $val <= $indexBatas; }));

        $query = TagihanSpp::with(['siswa.kelas', 'tahun_ajaran'])
            ->where('status', 'Belum Lunas')
            ->whereIn('bulan', $bulanJatuhTempo);
        
        if ($request->tahun_filter) {
            $query->whereIn('tahun_ajaran_id', TahunAjaran::where('tahun', $request->tahun_filter)->pluck('id'));
        } else {
            $tahunAktif = TahunAjaran::where('is_active', 1)->first();
            if ($tahunAktif) $query->whereIn('tahun_ajaran_id', TahunAjaran::where('tahun', $tahunAktif->tahun)->pluck('id'));
        }

        if ($request->kelas_filter) $query->whereHas('siswa', function($q) use ($request) { $q->where('kelas_id', $request->kelas_filter); });

        $data = $query->get()->groupBy('siswa_id');
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\TunggakanExport($data), 'Laporan_Tunggakan_SPP.xlsx');
    }
}