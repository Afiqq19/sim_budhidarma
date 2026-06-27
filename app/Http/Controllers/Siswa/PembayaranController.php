<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\TagihanSpp;
use App\Models\TahunAjaran;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $siswa = Siswa::where('user_id', $user->id)->first();
        $tahunAktifObj = TahunAjaran::where('is_active', 1)->first();

        // 1. AMBIL DAFTAR TAHUN UNIK YANG DIMILIKI SISWA (Berdasarkan jejak database)
        $listTahunArray = TagihanSpp::where('siswa_id', $siswa->id)
            ->join('tahun_ajarans', 'tagihan_spps.tahun_ajaran_id', '=', 'tahun_ajarans.id')
            ->select('tahun_ajarans.tahun')
            ->distinct()
            ->pluck('tahun')
            ->toArray();

        // 🔥 2. LOGIKA JALUR NINJA: FILTER & DEFAULT TAHUN BERDASARKAN STATUS 🔥
        if ($siswa->status_siswa == 'Alumni' || $siswa->status_siswa == 'Pindah') {
            // Jika Alumni/Pindah: Dropdown HANYA berisi tahun sejarahnya saja
            $listTahun = collect($listTahunArray)->sortDesc()->values();

            // Default tahun yang dilihat: Tahun TERAKHIR dia punya tagihan (Bukan tahun aktif saat ini)
            $selectedTahun = $request->get('tahun', $listTahun->first() ?? null);
        } else {
            // Jika Siswa Aktif: Pastikan Tahun Aktif selalu muncul di dropdown,
            // walau mungkin bendahara belum sempat membuatkan tagihannya.
            if ($tahunAktifObj && !in_array($tahunAktifObj->tahun, $listTahunArray)) {
                $listTahunArray[] = $tahunAktifObj->tahun;
            }
            $listTahun = collect($listTahunArray)->sortDesc()->values();

            // Default tahun yang dilihat: Tahun Aktif saat ini
            $selectedTahun = $request->get('tahun', $tahunAktifObj->tahun ?? $listTahun->first());
        }

        // --- PENGAMAN JIKA DATA BENAR-BENAR KOSONG ---
        if (!$selectedTahun) {
            $tagihans = collect();
            $totalTunggakanWajib = 0;
            $teksRangeBulan = '-';
            $bulanSekarang = now()->translatedFormat('F');
            $urutanBulan = ['Juli' => 1, 'Agustus' => 2, 'September' => 3, 'Oktober' => 4, 'November' => 5, 'Desember' => 6, 'Januari' => 7, 'Februari' => 8, 'Maret' => 9, 'April' => 10, 'Mei' => 11, 'Juni' => 12];
            $indexSekarang = $urutanBulan[$bulanSekarang] ?? 10;

            return view('siswa.tagihan.index', compact(
                'siswa',
                'tahunAktifObj',
                'listTahun',
                'tagihans',
                'totalTunggakanWajib',
                'teksRangeBulan',
                'selectedTahun',
                'indexSekarang',
                'urutanBulan',
                'bulanSekarang'
            ));
        }

        // 3. AMBIL DATA TAGIHAN BERDASARKAN TAHUN YANG TERPILIH
        // Cari ID Tahun Ajaran yang tahunnya sama dengan $selectedTahun
        $tahunIds = TahunAjaran::where('tahun', $selectedTahun)->pluck('id');

        $tagihans = TagihanSpp::where('siswa_id', $siswa->id)
            ->whereIn('tahun_ajaran_id', $tahunIds)
            ->orderBy('id', 'asc') // Urut dari Juli s/d Juni
            ->get();

        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $bulanSekarang = now()->translatedFormat('F');
        $urutanBulan = ['Juli' => 1, 'Agustus' => 2, 'September' => 3, 'Oktober' => 4, 'November' => 5, 'Desember' => 6, 'Januari' => 7, 'Februari' => 8, 'Maret' => 9, 'April' => 10, 'Mei' => 11, 'Juni' => 12];
        $indexSekarang = $urutanBulan[$bulanSekarang] ?? 10;

        // 4. GENERATE TOKEN (Hanya generate jika siswa melihat tagihan di Tahun Aktif)
        if (isset($tahunAktifObj) && $selectedTahun == $tahunAktifObj->tahun) {
            foreach ($tagihans as $t) {
                if ($t->status == 'Belum Lunas' && !$t->snap_token) {
                    $params = [
                        'transaction_details' => ['order_id' => 'SPP-' . $t->id . '-' . time(), 'gross_amount' => $t->nominal],
                        'customer_details' => ['first_name' => $siswa->nama_lengkap, 'email' => $user->email ?? 'no-email@sekolah.com']
                    ];
                    try {
                        $t->snap_token = Snap::getSnapToken($params);
                        $t->save();
                    } catch (\Exception $e) {
                        dd("ERROR DARI MIDTRANS: " . $e->getMessage());
                    }
                }
            }
        }

        // 5. HITUNG TUNGGAKAN JATUH TEMPO (Hanya dihitung jika di Tahun Aktif)
        $totalTunggakanWajib = 0;
        $teksRangeBulan = '-';
        if (isset($tahunAktifObj) && $selectedTahun == $tahunAktifObj->tahun) {
            $tagihanWajib = $tagihans->filter(function ($t) use ($urutanBulan, $indexSekarang) {
                $idx = $urutanBulan[$t->bulan] ?? 0;
                return $t->status == 'Belum Lunas' && $idx <= $indexSekarang;
            });
            $totalTunggakanWajib = $tagihanWajib->sum('nominal');
            if ($tagihanWajib->count() > 0) {
                $teksRangeBulan = $tagihanWajib->first()->bulan . ' - ' . $tagihanWajib->last()->bulan;
            }
        }

        return view('siswa.tagihan.index', compact(
            'siswa',
            'tahunAktifObj',
            'listTahun',
            'tagihans',
            'totalTunggakanWajib',
            'teksRangeBulan',
            'selectedTahun',
            'indexSekarang',
            'urutanBulan',
            'bulanSekarang'
        ));
    }

    /**
     * PROSES PENYIMPANAN OTOMATIS SETELAH BAYAR DI MIDTRANS
     */
    public function updateStatus(\Illuminate\Http\Request $request)
    {
        // 1. Cari tagihan berdasarkan token Midtrans
        $tagihan = TagihanSpp::where('snap_token', $request->snap_token)->first();

        if ($tagihan) {
            // 2. Ubah status tagihan siswa menjadi Lunas
            $tagihan->update(['status' => 'Lunas']);

            // 3. CATAT KE BUKU KAS BENDAHARA (Tabel pembayarans)
            // Kita cek dulu agar tidak terjadi pencatatan ganda (double input)
            $cekPembayaran = \App\Models\Pembayaran::where('tagihan_spp_id', $tagihan->id)->first();

            if (!$cekPembayaran) {
                \App\Models\Pembayaran::create([
                    'order_id' => 'VA-' . time() . '-' . $tagihan->id,
                    'tagihan_spp_id' => $tagihan->id,
                    'siswa_id' => $tagihan->siswa_id,
                    'pegawai_id' => null, // Dikosongkan karena bayar online, bukan lewat kasir fisik
                    'tanggal_bayar' => now(),
                    'jumlah_bayar' => $tagihan->nominal,
                    'metode_pembayaran' => 'Virtual Account (Online)',
                    'status_bayar' => 'success',
                    'snap_token' => $request->snap_token
                ]);
            }

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }
    /**
     * PROSES CALLBACK SERVER-TO-SERVER DARI MIDTRANS (TIDAK BOLEH DIHAPUS)
     * Rute ini dipanggil otomatis oleh mesin Midtrans di belakang layar.
     */
    public function callback(Request $request)
    {
        // 1. Ambil kunci rahasia dan data kiriman Midtrans
        $serverKey = config('midtrans.server_key');
        $orderId = $request->order_id;
        $statusCode = $request->status_code;
        $grossAmount = $request->gross_amount;
        $transactionStatus = $request->transaction_status;

        // 2. Verifikasi keamanan (Pastikan yang mengirim pesan ini benar-benar Midtrans, bukan hacker)
        $hashed = hash("sha512", $orderId . $statusCode . $grossAmount . $serverKey);

        if ($hashed == $request->signature_key) {

            // 3. Jika statusnya berhasil dibayar (settlement)
            if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {

                // Ambil ID Tagihan dari order_id (Format Bapak saat generate token: SPP-{id}-{waktu})
                $pecahan = explode('-', $orderId);
                $tagihanId = $pecahan[1] ?? null;

                if ($tagihanId) {
                    $tagihan = TagihanSpp::find($tagihanId);

                    // Pastikan tagihan ditemukan dan belum lunas
                    if ($tagihan && $tagihan->status != 'Lunas') {
                        // Ubah jadi Lunas!
                        $tagihan->update(['status' => 'Lunas']);

                        // Catat ke buku kas (Tabel Pembayaran)
                        $cekPembayaran = \App\Models\Pembayaran::where('tagihan_spp_id', $tagihan->id)->first();

                        if (!$cekPembayaran) {
                            \App\Models\Pembayaran::create([
                                'order_id' => $orderId,
                                'tagihan_spp_id' => $tagihan->id,
                                'siswa_id' => $tagihan->siswa_id,
                                'pegawai_id' => null, // Karena bayar online
                                'tanggal_bayar' => now(),
                                'jumlah_bayar' => $tagihan->nominal,
                                'metode_pembayaran' => $request->payment_type ?? 'Midtrans Online',
                                'status_bayar' => 'success',
                                'snap_token' => $tagihan->snap_token
                            ]);
                        }
                    }
                }
            }
        }

        // 4. Wajib mengembalikan sinyal 200 OK ke Midtrans agar Midtrans tahu pesannya sudah diterima
        return response()->json(['status' => 'success', 'message' => 'Callback diterima']);
    }
    /**
     * FUNGSI UNTUK MENCETAK BUKTI BAYAR KHUSUS SISWA
     */
    public function cetak($id)
    {
        $user = Auth::user();
        $siswa = Siswa::where('user_id', $user->id)->first();
        
        // 1. Ambil data transaksi beserta relasi kelas dan tahun ajaran (Sesuai dengan controller bendahara)
        $trx = \App\Models\Pembayaran::with(['siswa.kelas', 'tagihan_spp.tahun_ajaran', 'pegawai'])->findOrFail($id);
        
        // 2. Keamanan Lapis Dua: Pastikan siswa HANYA BISA mencetak struk miliknya sendiri!
        if($trx->siswa_id != $siswa->id) {
            abort(403, 'Akses Ilegal: Ini bukan struk Anda!');
        }

        // 3. Panggil view yang tepat dengan membawa variabel $trx
        return view('bendahara.laporan.struk', compact('trx')); 
    }
}
