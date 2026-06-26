<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\TagihanSpp;
use App\Models\TahunAjaran;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $siswas = [];
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $siswas = Siswa::with('kelas') 
                        ->where('status_siswa', 'Aktif')
                        ->where(function($query) use ($search) {
                            $query->where('nama_lengkap', 'like', '%' . $search . '%')
                                  ->orWhere('nisn', 'like', '%' . $search . '%');
                        })->get();
        }
        return view('bendahara.transaksi.index', compact('siswas'));
    }

    // Bawaan Laravel untuk menangkap URL ?ta=...
    public function show(Request $request, $id)
    {
        $siswa = Siswa::with('kelas')->findOrFail($id);
        
        // 1. Ambil semua nama tahun yang ada di database (Unik, Ganjil/Genap gabung)
        $listTahun = TahunAjaran::select('tahun')->distinct()->pluck('tahun');

        // 2. Cek Tahun Aktif default dari sistem
        $tahunAktifDB = TahunAjaran::where('is_active', 1)->first();
        
        // 3. Logika Filter: Jika ada pilihan di URL, pakai itu. Jika tidak, pakai yang aktif
        $selectedTahun = $request->ta ?? ($tahunAktifDB ? $tahunAktifDB->tahun : null);

        $bulan_spp = [
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember', 
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni'
        ];

        $tagihans = [];
        if ($selectedTahun) {
            // Tarik tagihan berdasarkan tahun yang DIPILIH di dropdown
            $idsTahunIni = TahunAjaran::where('tahun', $selectedTahun)->pluck('id');
            
            $tagihansRaw = TagihanSpp::where('siswa_id', $id)
                                  ->whereIn('tahun_ajaran_id', $idsTahunIni)
                                  ->get();
            
            foreach ($tagihansRaw as $t) {
                if (!isset($tagihans[$t->bulan]) || $t->status == 'Lunas') {
                    $tagihans[$t->bulan] = $t;
                }
            }
        }

        // Kirim variabel $selectedTahun dan $listTahun ke View
        return view('bendahara.transaksi.show', compact('siswa', 'bulan_spp', 'tagihans', 'selectedTahun', 'listTahun'));
    }

    public function storeManual(Request $request, $id)
    {
        $request->validate([
            'bulan' => 'required|string',
            'tahun' => 'required|string', // Wajib tahu ini bayar untuk tahun ajaran mana
        ]);

        // Cari ID untuk Tahun Ajaran yang sedang tampil di layar kasir
        $idsTahunIni = TahunAjaran::where('tahun', $request->tahun)->pluck('id');

        $tagihansToUpdate = TagihanSpp::where('siswa_id', $id)
                             ->whereIn('tahun_ajaran_id', $idsTahunIni)
                             ->where('bulan', $request->bulan)
                             ->get();

        if ($tagihansToUpdate->count() > 0) {
            
            $tagihanUtama = $tagihansToUpdate->first();

            // --- GEMBOK KEAMANAN (Mencegah Double Click / Bayar Ulang) ---
            if ($tagihanUtama->status == 'Lunas') {
                return redirect()->back()->with('error', 'Gagal memproses! Tagihan SPP bulan ' . $request->bulan . ' sudah lunas sebelumnya.');
            }
            // -------------------------------------------------------------

            foreach ($tagihansToUpdate as $tagihan) {
                $tagihan->status = 'Lunas';
                $tagihan->save();
            }

            // --- AWAL PERUBAHAN NOMOR TRANSAKSI (ORDER ID) ---
            $siswaData = Siswa::findOrFail($id);
            
            // Konversi nama bulan ke angka 2 digit
            $arrayBulan = [
                'Januari' => '01', 'Februari' => '02', 'Maret' => '03',
                'April' => '04', 'Mei' => '05', 'Juni' => '06',
                'Juli' => '07', 'Agustus' => '08', 'September' => '09',
                'Oktober' => '10', 'November' => '11', 'Desember' => '12'
            ];
            $bulanAngka = $arrayBulan[$request->bulan] ?? date('m');
            
            // Ambil 2 digit tahun saat ini (Contoh: 2026 jadi 26)
            $tahunAngka = date('y'); 
            
            // Format Final: SPP-BulanTahunNISN + 3 Angka Acak (Contoh: SPP-01260096048539123)
            $orderIdBaru = 'SPP-' . date('ymd')  . $tagihan->id;
            //$orderIdBaru = 'SPP-' . $bulanAngka . $tahunAngka . $siswaData->nisn . rand(100, 999);
            // --- AKHIR PERUBAHAN ---

            \App\Models\Pembayaran::create([
                'order_id' => $orderIdBaru,
                'tagihan_spp_id' => $tagihanUtama->id,
                'siswa_id' => $id,
                'pegawai_id' => \Illuminate\Support\Facades\Auth::user()->pegawai->id ?? null,
                'tanggal_bayar' => now(),
                'jumlah_bayar' => $tagihanUtama->nominal,
                'metode_pembayaran' => 'Tunai',
                'status_bayar' => 'success', // Memastikan statusnya langsung lunas
            ]);
            
            return redirect()->back()->with('success', 'Hore! Uang SPP Bulan ' . $request->bulan . ' (' . $request->tahun . ') Berhasil Diterima!');
        } else {
            return redirect()->back()->with('error', 'Tagihan untuk bulan tersebut tidak ditemukan!');
        }
    }
    // =========================================================
    // FUNGSI UNTUK CETAK REKAP SPP LUNAS (KERTAS HVS)
    // =========================================================
    public function cetakRekap($siswa_id, $tahun)
    {
        // 1. Ambil data siswa
        $siswa = \App\Models\Siswa::findOrFail($siswa_id);

        // 2. Dekode URL jika tahun ajaran terbaca 2028%2F2029 menjadi 2028/2029
        $tahun = urldecode($tahun);

        // 3. 🔥 PERBAIKAN: Cari dulu ID Tahun Ajarannya dari database 🔥
        $tahunAjaran = \App\Models\TahunAjaran::where('tahun', $tahun)->first();
        
        if (!$tahunAjaran) {
            return back()->with('error', 'Data Tahun Ajaran tidak ditemukan di sistem.');
        }

        // 4. 🔥 PERBAIKAN: Gunakan tahun_ajaran_id (sesuai database Bapak) 🔥
        $tagihanLunas = \App\Models\TagihanSpp::where('siswa_id', $siswa_id)
            ->where('tahun_ajaran_id', $tahunAjaran->id)
            ->where('status', 'Lunas')
            ->orderBy('id', 'asc') // Urutkan dari bulan pertama dibayar
            ->get();

        // 5. Jika ternyata belum ada yang lunas, kembalikan dengan pesan error
        if ($tagihanLunas->isEmpty()) {
            return back()->with('error', 'Tidak dapat mencetak struk. Belum ada tagihan SPP yang lunas untuk Tahun Ajaran ' . $tahun . '.');
        }

        // 6. Hitung total uang yang sudah dibayarkan
        $totalBayar = $tagihanLunas->sum('nominal');

        // 7. Tampilkan ke file halaman cetak (View)
        return view('bendahara.transaksi.cetak_rekap', compact('siswa', 'tagihanLunas', 'tahun', 'totalBayar'));
    }
}