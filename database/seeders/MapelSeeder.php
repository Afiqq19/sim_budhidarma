<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mapel; // Pastikan model Mapel dipanggil

class MapelSeeder extends Seeder
{
    public function run(): void
    {
        $mapels = [
            // ==========================================
            // A. MUATAN NASIONAL (Kelompok A)
            // ==========================================
            [
                'kode_mapel' => 'PABP', 
                'nama_mapel' => 'Pendidikan Agama dan Budi Pekerti', 
                'kelompok' => 'A', 
                'kkm' => 75
            ],
            [
                'kode_mapel' => 'PPKN', 
                'nama_mapel' => 'Pendidikan Pancasila dan Kewarganegaraan', 
                'kelompok' => 'A', 
                'kkm' => 75
            ],
            [
                'kode_mapel' => 'BIND', 
                'nama_mapel' => 'Bahasa Indonesia', 
                'kelompok' => 'A', 
                'kkm' => 75
            ],
            [
                'kode_mapel' => 'MTK', 
                'nama_mapel' => 'Matematika', 
                'kelompok' => 'A', 
                'kkm' => 75
            ],
            [
                'kode_mapel' => 'SJI', 
                'nama_mapel' => 'Sejarah Indonesia', 
                'kelompok' => 'A', 
                'kkm' => 75
            ],
            [
                'kode_mapel' => 'BING', 
                'nama_mapel' => 'Bahasa Inggris', 
                'kelompok' => 'A', 
                'kkm' => 75
            ],

            // ==========================================
            // B. MUATAN KEWILAYAHAN (Kelompok B)
            // ==========================================
            [
                'kode_mapel' => 'SBD', 
                'nama_mapel' => 'Seni Budaya', 
                'kelompok' => 'B', 
                'kkm' => 75
            ],
            [
                'kode_mapel' => 'PJOK', 
                'nama_mapel' => 'Pendidikan Jasmani, Olahraga dan Kesehatan', 
                'kelompok' => 'B', 
                'kkm' => 75
            ],
            [
                'kode_mapel' => 'MLK', 
                'nama_mapel' => 'Muatan Lokal', 
                'kelompok' => 'B', 
                'kkm' => 75
            ],

            // ==========================================
            // C2. DASAR PROGRAM KEAHLIAN (Kelompok C2)
            // ==========================================
            [
                'kode_mapel' => 'SISKOM', 
                'nama_mapel' => 'Sistem Komputer', 
                'kelompok' => 'C2', 
                'kkm' => 75
            ],
            [
                'kode_mapel' => 'KJD', 
                'nama_mapel' => 'Komputer dan Jaringan Dasar', 
                'kelompok' => 'C2', 
                'kkm' => 75
            ],
            [
                'kode_mapel' => 'PDAS', 
                'nama_mapel' => 'Pemrograman Dasar', 
                'kelompok' => 'C2', 
                'kkm' => 75
            ],
            [
                'kode_mapel' => 'DDG', 
                'nama_mapel' => 'Dasar Desain Grafis', 
                'kelompok' => 'C2', 
                'kkm' => 75
            ],

            // ==========================================
            // C3. KOMPETENSI KEAHLIAN (Kelompok C3)
            // ==========================================
            [
                'kode_mapel' => 'WAN', 
                'nama_mapel' => 'Teknologi Jaringan Berbasis Luas (WAN)', 
                'kelompok' => 'C3', 
                'kkm' => 75
            ],
            [
                'kode_mapel' => 'AIJ', 
                'nama_mapel' => 'Administrasi Infrastruktur Jaringan', 
                'kelompok' => 'C3', 
                'kkm' => 75
            ],
            [
                'kode_mapel' => 'ASJ', 
                'nama_mapel' => 'Administrasi Sistem Jaringan', 
                'kelompok' => 'C3', 
                'kkm' => 75
            ],
            [
                'kode_mapel' => 'TLJ', 
                'nama_mapel' => 'Teknologi Layanan Jaringan', 
                'kelompok' => 'C3', 
                'kkm' => 75
            ],
            [
                'kode_mapel' => 'PKK', 
                'nama_mapel' => 'Produk Kreatif dan Kewirausahaan', 
                'kelompok' => 'C3', 
                'kkm' => 75
            ],
        ];

        foreach ($mapels as $mapel) {
            Mapel::create($mapel);
        }
    }
}