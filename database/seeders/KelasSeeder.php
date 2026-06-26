<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelas;
use App\Models\Jurusan; // Kita butuh ini untuk mengambil ID Jurusan

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil ID dari jurusan yang sudah dibuat sebelumnya
        $tkj_id = Jurusan::where('kode_jurusan', 'TKJ')->first()->id ?? 1;
        $rpl_id = Jurusan::where('kode_jurusan', 'RPL')->first()->id ?? 2;
        
        $kelases = [
            // KELAS TKJ
            ['jurusan_id' => $tkj_id, 'kode_kelas' => 'X-TKJ-1', 'nama_kelas' => 'X TKJ 1'],
            ['jurusan_id' => $tkj_id, 'kode_kelas' => 'X-TKJ-2', 'nama_kelas' => 'X TKJ 2'],
            ['jurusan_id' => $tkj_id, 'kode_kelas' => 'XI-TKJ-1', 'nama_kelas' => 'XI TKJ 1'],
            ['jurusan_id' => $tkj_id, 'kode_kelas' => 'XII-TKJ-1', 'nama_kelas' => 'XII TKJ 1'],

            // KELAS RPL
            ['jurusan_id' => $rpl_id, 'kode_kelas' => 'X-RPL-1', 'nama_kelas' => 'X RPL 1'],
            ['jurusan_id' => $rpl_id, 'kode_kelas' => 'XI-RPL-1', 'nama_kelas' => 'XI RPL 1'],
            ['jurusan_id' => $rpl_id, 'kode_kelas' => 'XII-RPL-1', 'nama_kelas' => 'XII RPL 1'],
        ];

        foreach ($kelases as $kelas) {
            Kelas::create($kelas);
        }
    }
}