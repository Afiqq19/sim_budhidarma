<?php

namespace App\Http\Controllers\Admin; // 🔥 Namespace diubah ke Admin

use App\Http\Controllers\Controller;
use App\Exports\PembayaranExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Models\Pembayaran;
use Carbon\Carbon;

class RiwayatTransaksiController extends Controller
{
    public function index(Request $request)
    {
        // 1. Query Dasar Riwayat (Semua yang tidak gagal)
        $query = Pembayaran::with(['siswa.kelas', 'tagihan_spp.tahun_ajaran', 'pegawai'])
                           ->where('status_bayar', '!=', 'failed')
                           ->orderBy('created_at', 'desc');

        // 2. Setup Default Kartu: Pemasukan Hari Ini
        $judulRekap = "Pemasukan Hari Ini";
        $queryRekap = Pembayaran::where('status_bayar', '!=', 'failed')
                                ->whereDate('tanggal_bayar', Carbon::today());

        // 3. Filter Pencarian (Nama / NISN / ORDER ID)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                // Cari dari Nama atau NISN Siswa
                $q->whereHas('siswa', function($sub) use ($search) {
                    $sub->where('nama_lengkap', 'like', '%' . $search . '%')
                      ->orWhere('nisn', 'like', '%' . $search . '%');
                })
                // ATAU cari dari Nomor Transaksi (Order ID)
                ->orWhere('order_id', 'like', '%' . $search . '%');
            });
            
            $judulRekap = "Total Sesuai Pencarian";
            $queryRekap = clone $query; // Hitung rekap berdasarkan hasil pencarian
        }

        // 4. Filter Berdasarkan Bulan
        if ($request->has('bulan_filter') && $request->bulan_filter != '') {
            $explode = explode('-', $request->bulan_filter);
            $filterTahun = $explode[0];
            $filterBulan = $explode[1];
            
            $query->whereMonth('tanggal_bayar', $filterBulan)
                  ->whereYear('tanggal_bayar', $filterTahun);
            
            // Ubah format angka bulan jadi nama bulan (Januari, Februari, dst)
            Carbon::setLocale('id');
            $namaBulan = Carbon::createFromFormat('Y-m', $request->bulan_filter)->translatedFormat('F Y');
            
            $judulRekap = "Pemasukan Bulan " . $namaBulan;
            $queryRekap = clone $query; // Hitung rekap berdasarkan bulan tersebut
        }

        // 5. Filter Berdasarkan Metode Pembayaran
        if ($request->has('metode_filter') && $request->metode_filter != '') {
            if ($request->metode_filter == 'tunai') {
                $query->whereIn('metode_pembayaran', ['Tunai', 'Tunai / Manual']);
                $queryRekap->whereIn('metode_pembayaran', ['Tunai', 'Tunai / Manual']);
            } else {
                $query->whereNotIn('metode_pembayaran', ['Tunai', 'Tunai / Manual']);
                $queryRekap->whereNotIn('metode_pembayaran', ['Tunai', 'Tunai / Manual']);
            }
        }

        // Eksekusi total uang dan pagination tabel
        $totalRekap = $queryRekap->sum('jumlah_bayar');
        $riwayats = $query->paginate(15)->withQueryString();

        // 🔥 Return diarahkan ke view admin yang kita buat di percakapan sebelumnya
        return view('admin.riwayat.index', compact('riwayats', 'judulRekap', 'totalRekap'));
    }

    // FUNGSI BARU UNTUK CETAK STRUK (Akses Yayasan)
    public function cetak($id)
    {
        $trx = Pembayaran::with(['siswa.kelas', 'tagihan_spp.tahun_ajaran', 'pegawai'])->findOrFail($id);
        
        // 🔥 Kita bisa numpang pakai desain struk punya Bendahara biar Bapak tidak perlu repot bikin file struk lagi
        return view('bendahara.laporan.struk', compact('trx'));
    }

    // FUNGSI BARU UNTUK EXCEL
    public function exportExcel(Request $request) 
    {
        return Excel::download(new PembayaranExport($request), 'Laporan_SPP_Yayasan_'.date('d-m-Y').'.xlsx');
    }
}