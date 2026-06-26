<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Pegawai;
use App\Models\WaliKelas;
use App\Models\Siswa;
use App\Models\Kelas;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1. Buat Data Admin Yayasan (Hardcode karena cuma 1)
        User::create([
            'name' => 'Admin Yayasan',
            'username' => 'admin',
            'password' => Hash::make('rahasia123'),
            'role' => 'admin',
        ]);

        // 2. Import Data Pegawai (TU & Bendahara)
        $this->importJurusan();
        $this->importKelas(); // Kelas harus ada sebelum siswa masuk!

        // 3. BARU IMPORT DATA MANUSIA
        $this->importPegawai();
        $this->importWaliKelas();
        $this->importSiswa();
        $this->importMapel();


    }

    private function importPegawai()
    {
        $csvFile = fopen(base_path('database/data/pegawai.csv'), 'r');
        $firstline = true;

        // UBAH JADI SEPERTI INI:
        while (($data = fgetcsv($csvFile, 2000, ';')) !== FALSE) {
            if (!$firstline) {
                $user = User::create([
                    'name'     => $data[0], // nama_lengkap
                    'username' => $data[1], // username
                    'password' => Hash::make('rahasia123'), // password
                    'role'     => $data[3], // role
                ]);

                Pegawai::create([
                    'user_id'      => $user->id,
                    'nama_lengkap' => $data[0],
                    'nip'          => $data[4],
                    'jk'           => $data[5],
                    'no_hp'        => $data[6],
                    'alamat'       => $data[7],
                    'jabatan'      => $data[8],
                ]);
            }
            $firstline = false;
        }
        fclose($csvFile);
    }

    private function importWaliKelas()
    {
        $csvFile = fopen(base_path('database/data/wali_kelas.csv'), 'r');
        $firstline = true;

        // UBAH JADI SEPERTI INI:
        // UBAH JADI SEPERTI INI:
        while (($data = fgetcsv($csvFile, 2000, ';')) !== FALSE) {
            
            // 🔥 PENGAMAN: Jika baris ini kosong atau datanya kurang dari 2 kolom, LEWATI! 🔥
            if (count($data) < 2) {
                continue;
            }

            if (!$firstline) {
                $user = User::create([
                    'name'     => $data[0],
                    'username' => $data[1],
                    'password' => Hash::make('rahasia123'),
                    'role'     => 'wali_kelas',
                ]);

                // Cari ID Kelas berdasarkan kode_kelas dari CSV
                $kelas = Kelas::where('kode_kelas', $data[8])->first();

                WaliKelas::create([
                    'user_id'      => $user->id,
                    'kelas_id'     => $kelas ? $kelas->id : null,
                    'nama_lengkap' => $data[0],
                    'nrg'          => $data[3],
                    'nip'          => $data[4],
                    'jk'           => $data[5],
                    'no_hp'        => $data[6],
                    'alamat'       => $data[7],
                ]);
            }
            $firstline = false;
        }
        fclose($csvFile);
    }

    private function importSiswa()
    {
        $csvFile = fopen(base_path('database/data/siswa.csv'), 'r');
        $firstline = true;

        // UBAH JADI SEPERTI INI:
        while (($data = fgetcsv($csvFile, 2000, ';')) !== FALSE) {
            if (!$firstline) {
                // Username dan Password default menggunakan NISN
                $user = User::create([
                    'name'     => $data[1], // nama_lengkap
                    'username' => $data[0], // nisn
                    'password' => Hash::make($data[0]), // password = nisn
                    'role'     => 'siswa',
                ]);

                // Cari ID Kelas berdasarkan kode_kelas
                $kelas = Kelas::where('kode_kelas', $data[9])->first();

                Siswa::create([
                    'user_id'        => $user->id,
                    'kelas_id'       => $kelas ? $kelas->id : 1, // fallback ke id 1 jika tidak ketemu
                    'nisn'           => $data[0],
                    'nama_lengkap'   => $data[1],
                    'jk'             => $data[2],
                    'tempat_lahir'   => $data[3],
                    'tanggal_lahir'  => $data[4],
                    'alamat'         => $data[5],
                    'no_hp_siswa'    => $data[6],
                    'nama_orang_tua' => $data[7],
                    'no_hp_ortu'     => $data[8],
                    'status_siswa'   => 'Aktif',
                ]);
            }
            $firstline = false;
        }
        fclose($csvFile);
    }
    private function importJurusan()
    {
        $csvFile = fopen(base_path('database/data/jurusan.csv'), 'r');
        $firstline = true;

        while (($data = fgetcsv($csvFile, 2000, ';')) !== FALSE) {
            if (!$firstline) {
                \App\Models\Jurusan::create([
                    'kode_jurusan' => $data[0],
                    'nama_jurusan' => $data[1],
                ]);
            }
            $firstline = false;
        }
        fclose($csvFile);
    }

        private function importMapel()
    {
        // Langsung arahkan ke satu file CSV yang sudah digabung
        // Pastikan nama filenya sesuai, misalnya 'mapel.csv'
        $csvFile = fopen(base_path('database/data/mapel.csv'), 'r');
        
        $firstline = true;

        // Tetap menggunakan titik koma (;) sebagai pemisah
        while (($data = fgetcsv($csvFile, 2000, ';')) !== FALSE) {
            if (!$firstline) {
                \App\Models\Mapel::create([
                    'kode_mapel' => $data[0],
                    'nama_mapel' => $data[1],
                    'kelompok'   => $data[2], // Mengambil A, B, C1, C2, atau C3 dari CSV
                    'kkm'        => $data[3],
                ]);
            }
            $firstline = false;
        }
        
        fclose($csvFile);
    }

        private function importKelas()
    {
        $csvFile = fopen(base_path('database/data/kelas.csv'), 'r');
        $firstline = true;

        while (($data = fgetcsv($csvFile, 2000, ';')) !== FALSE) {
            if (!$firstline) {
                // Cari jurusan berdasarkan kode_jurusan (kolom index 0 di CSV)
                $jurusan = \App\Models\Jurusan::where('kode_jurusan', $data[0])->first();

                // Jika jurusannya ketemu, baru kelasnya dibuat
                if ($jurusan) {
                    \App\Models\Kelas::create([
                        'jurusan_id' => $jurusan->id,
                        'kode_kelas' => $data[1],
                        'nama_kelas' => $data[2],
                    ]);
                }
            }
            $firstline = false;
        }
        
        fclose($csvFile);
    }
}