<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jurusan; // Panggil model Jurusan

class JurusanSeeder extends Seeder
{
    public function run(): void
    {
        $jurusans = [
            [
                'kode_jurusan' => 'TKJ',
                'nama_jurusan' => 'Teknik Komputer dan Jaringan',
            ],
            [
                'kode_jurusan' => 'RPL',
                'nama_jurusan' => 'Rekayasa Perangkat Lunak',
            ],
            [
                'kode_jurusan' => 'TKR',
                'nama_jurusan' => 'Teknik Kendaraan Ringan',
            ],
        ];

        foreach ($jurusans as $jurusan) {
            Jurusan::create($jurusan);
        }
    }
}