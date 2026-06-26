<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TunggakanExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $data;

    public function __construct($data) {
        $this->data = $data;
    }

    public function collection() {
        return $this->data;
    }

    public function headings(): array {
        return ["NAMA SISWA", "NISN", "KELAS", "TAHUN AJARAN", "BULAN TERTUNGGAK", "TOTAL TUNGGAKAN"];
    }

    public function map($item): array {
        $tagihans = $item; // Grup tagihan per siswa
        $siswa = $tagihans->first()->siswa;
        $listBulan = $tagihans->pluck('bulan')->implode(', ');
        
        return [
            $siswa->nama_lengkap ?? '-',
            $siswa->nisn ?? '-',
            $siswa->kelas->nama_kelas ?? '-',
            $tagihans->first()->tahun_ajaran->tahun ?? '-',
            $listBulan,
            $tagihans->sum('nominal'),
        ];
    }
}