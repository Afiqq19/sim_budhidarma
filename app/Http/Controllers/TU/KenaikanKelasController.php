<?php

namespace App\Http\Controllers\TU;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Siswa;

class KenaikanKelasController extends Controller
{
    // Menampilkan Dashboard Kenaikan Serentak
    public function index()
    {
        $ta_aktif = \App\Models\TahunAjaran::where('is_active', true)->first();
        // Ambil semua kelas beserta siswanya yang masih aktif
        $kelas_dengan_siswa = Kelas::with(['siswas' => function($query) {
            $query->where('status_siswa', 'Aktif')->orderBy('nama_lengkap', 'asc');
        }])->orderBy('nama_kelas', 'asc')->get();

        return view('tu.kenaikan_kelas.index', compact('kelas_dengan_siswa', 'ta_aktif'));
    }

    // Mesin Inti Kenaikan Kelas 1 Sekolah
    public function prosesSerentak(Request $request)
    {
        // Ambil ID siswa yang ditandai tinggal kelas (jika ada)
        $tertahan_ids = $request->siswa_tertahan_ids ?? [];
        
        // Ambil SEMUA siswa yang statusnya masih Aktif
        $semua_siswa = Siswa::where('status_siswa', 'Aktif')->with('kelas')->get();
        $semua_kelas = Kelas::all();

        $count_naik = 0;
        $count_lulus = 0;
        $count_tertahan = count($tertahan_ids);
        $count_error = 0;

        foreach ($semua_siswa as $siswa) {
            // 1. CEK PENGECUALIAN: Jika siswa ini dicentang "Tinggal Kelas", maka Skip/Abaikan!
            if (in_array($siswa->id, $tertahan_ids)) {
                continue; 
            }

            $nama_kelas_asal = $siswa->kelas->nama_kelas;

            // 2. KELAS XII -> LULUS JADI ALUMNI
            if (str_contains($nama_kelas_asal, 'XII ')) {
                $siswa->update([
                    'status_siswa' => 'Alumni', 
                    'tahun_lulus' => date('Y')
                ]);
                $count_lulus++;
            } 
            // 3. KELAS XI -> NAIK KE KELAS XII
            elseif (str_contains($nama_kelas_asal, 'XI ')) {
                $nama_tujuan = str_replace('XI ', 'XII ', $nama_kelas_asal);
                $kelas_tujuan = $semua_kelas->where('nama_kelas', $nama_tujuan)->first();
                
                if ($kelas_tujuan) {
                    $siswa->update(['kelas_id' => $kelas_tujuan->id]);
                    $count_naik++;
                } else {
                    $count_error++; // Jika kelas XII pasangannya belum dibuat di Master Kelas
                }
            } 
            // 4. KELAS X -> NAIK KE KELAS XI
            elseif (str_contains($nama_kelas_asal, 'X ')) {
                $nama_tujuan = str_replace('X ', 'XI ', $nama_kelas_asal);
                $kelas_tujuan = $semua_kelas->where('nama_kelas', $nama_tujuan)->first();
                
                if ($kelas_tujuan) {
                    $siswa->update(['kelas_id' => $kelas_tujuan->id]);
                    $count_naik++;
                } else {
                    $count_error++;
                }
            }
            
        }

        $ta_aktif = \App\Models\TahunAjaran::where('is_active', true)->first();
        if($ta_aktif) {
            $ta_aktif->update(['is_kenaikan_selesai' => true]);
        }
        
        $pesan = "PROSES SUKSES! 🚀 $count_naik Siswa Naik Kelas, $count_lulus Siswa Lulus (Alumni), dan $count_tertahan Siswa Tinggal Kelas.";
        if($count_error > 0) {
            $pesan .= " (Catatan: Ada $count_error siswa gagal naik karena Kelas Tujuan belum dibuat di Master Kelas).";
        }

        return redirect()->route('tu.kenaikan-kelas.index')->with('success', $pesan);
    }
}