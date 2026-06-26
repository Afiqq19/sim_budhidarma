<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\TagihanSpp;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\DB;

class TagihanSppController extends Controller
{
    public function index()
    {
        $tahunAktif = TahunAjaran::where('is_active', 1)->first();
        $jumlahSiswaAktif = Siswa::where('status_siswa', 'Aktif')->count();
        $jumlahTagihanDibuat = 0;
        
        $nominal_tampil = 150000;

        if ($tahunAktif) {
            $idsTahunIni = TahunAjaran::where('tahun', $tahunAktif->tahun)->pluck('id');
            $jumlahTagihanDibuat = TagihanSpp::whereIn('tahun_ajaran_id', $idsTahunIni)->count();
            
            $nominal_tampil = $tahunAktif->nominal_spp;

            // 🔥 LOGIKA SINKRONISASI HARGA CERDAS 🔥
            // 1. Jika ini Semester GENAP, otomatis ambil harga dari Semester GANJIL di tahun yg sama
            if ($tahunAktif->semester == 'Genap') {
                $tahunGanjil = TahunAjaran::where('tahun', $tahunAktif->tahun)->where('semester', 'Ganjil')->first();
                if ($tahunGanjil) {
                    $nominal_tampil = $tahunGanjil->nominal_spp;
                }
            } 
            // 2. Jika ini Tahun Ajaran Baru (Ganjil) dan belum ada tagihan sama sekali
            else if ($jumlahTagihanDibuat == 0 && $nominal_tampil == 150000) {
                $tagihanTerakhir = TagihanSpp::latest('id')->first();
                if ($tagihanTerakhir) {
                    $nominal_tampil = $tagihanTerakhir->nominal; 
                }
            }
        }

        return view('bendahara.tagihan.index', compact('tahunAktif', 'jumlahSiswaAktif', 'jumlahTagihanDibuat', 'nominal_tampil'));
    }

    public function setNominal(Request $request)
    {
        $request->validate(['nominal' => 'required|numeric|min:1000']);
        $tahunAktif = TahunAjaran::where('is_active', 1)->firstOrFail();
        
        $idsTahunIni = TahunAjaran::where('tahun', $tahunAktif->tahun)->pluck('id');
        $cekTagihanSudahAda = TagihanSpp::whereIn('tahun_ajaran_id', $idsTahunIni)->exists();

        if ($cekTagihanSudahAda) {
            return redirect()->back()->with('error', 'Akses Ditolak! Anda tidak bisa mengubah Master Tarif karena tagihan untuk Tahun Ajaran ini sudah berjalan.');
        }

        TahunAjaran::where('tahun', $tahunAktif->tahun)->update([
            'nominal_spp' => $request->nominal
        ]);

        return redirect()->back()->with('success', 'Master Tarif SPP untuk T.A. ' . $tahunAktif->tahun . ' berhasil dikunci di angka Rp ' . number_format($request->nominal, 0, ',', '.'));
    }

    public function generateMassal(Request $request)
    {
        $tahunAktif = TahunAjaran::where('is_active', 1)->firstOrFail();
        $siswas = Siswa::where('status_siswa', 'Aktif')->get();
        $idsTahunIni = TahunAjaran::where('tahun', $tahunAktif->tahun)->pluck('id');
        
        $jumlahTagihanDibuat = TagihanSpp::whereIn('tahun_ajaran_id', $idsTahunIni)->count();
        
        $bulan_spp = [
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember', 
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni'
        ];

        $nominal_master = $tahunAktif->nominal_spp;

        // 🔥 KUNCI PERMANEN HARGA SINKRONISASI 🔥
        if ($tahunAktif->semester == 'Genap') {
            $tahunGanjil = TahunAjaran::where('tahun', $tahunAktif->tahun)->where('semester', 'Ganjil')->first();
            if ($tahunGanjil) {
                $nominal_master = $tahunGanjil->nominal_spp;
            }
        } 
        else if ($jumlahTagihanDibuat == 0 && $nominal_master == 150000) {
            $tagihanTerakhir = TagihanSpp::latest('id')->first();
            if ($tagihanTerakhir) {
                $nominal_master = $tagihanTerakhir->nominal;
            }
        }

        // Kunci harga ke tabel Tahun Ajaran secara permanen agar Ganjil & Genap selalu sinkron
        TahunAjaran::where('tahun', $tahunAktif->tahun)->update([
            'nominal_spp' => $nominal_master
        ]);

        DB::beginTransaction();
        try {
            $jumlahDibuat = 0;
            foreach ($siswas as $siswa) {
                foreach ($bulan_spp as $bulan) {
                    $cek = TagihanSpp::where('siswa_id', $siswa->id)
                                     ->whereIn('tahun_ajaran_id', $idsTahunIni)
                                     ->where('bulan', $bulan)
                                     ->exists();
                    if (!$cek) {
                        TagihanSpp::create([
                            'siswa_id' => $siswa->id,
                            'tahun_ajaran_id' => $tahunAktif->id,
                            'bulan' => $bulan,
                            'nominal' => $nominal_master, 
                            'status' => 'Belum Lunas'
                        ]);
                        $jumlahDibuat++;
                    }
                }
            }
            DB::commit();
            return redirect()->back()->with('success', "Proses Selesai! $jumlahDibuat lembar tagihan baru berhasil di-generate dengan tarif Rp " . number_format($nominal_master, 0, ',', '.'));
            
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}