<?php

namespace App\Exports;

use App\Models\Pembayaran;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PembayaranExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $request;

    public function __construct($request) {
        $this->request = $request;
    }

    public function query() {
        $query = Pembayaran::with(['siswa.kelas', 'tagihan_spp.tahun_ajaran', 'pegawai']);

        // Mengikuti filter yang ada di layar
        if ($this->request->bulan_filter) {
            $explode = explode('-', $this->request->bulan_filter);
            $query->whereMonth('tanggal_bayar', $explode[1])->whereYear('tanggal_bayar', $explode[0]);
        }
        
        return $query->orderBy('created_at', 'desc');
    }

    public function headings(): array {
        return ["TANGGAL", "ORDER ID", "NAMA SISWA", "NISN", "KELAS", "BULAN SPP", "NOMINAL", "METODE", "KASIR"];
    }

    public function map($pembayaran): array {
        return [
            $pembayaran->tanggal_bayar,
            $pembayaran->order_id,
            $pembayaran->siswa->nama_lengkap ?? '-',
            $pembayaran->siswa->nisn ?? '-',
            $pembayaran->siswa->kelas->nama_kelas ?? '-',
            $pembayaran->tagihan_spp->bulan ?? '-',
            $pembayaran->jumlah_bayar,
            $pembayaran->metode_pembayaran,
            $pembayaran->pegawai->nama_lengkap ?? 'Sistem VA',
        ];
    }
}