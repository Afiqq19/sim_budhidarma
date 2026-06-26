<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\WaliKelas;
use App\Models\Kelas;
use Illuminate\Support\Facades\Hash;

class WaliKelasSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil 2 kelas pertama 
        $kelases = Kelas::take(2)->get();

        foreach ($kelases as $index => $kelas) {
            $nrg = '100' . rand(1000, 9999);

            // 1. Buat Akun User (Wali Kelas)
            $user = User::create([
                'name' => 'Guru Wali ' . $kelas->nama_kelas,
                'username' => $nrg, // <-- INI YANG BARU DITAMBAHKAN
                'email' => 'guru' . ($index + 1) . '@smk.com', 
                'password' => Hash::make('password123'),
                'role' => 'wali_kelas' // <-- UBAH JADI PAKAI UNDERSCORE
            ]);

            // 2. Buat Data Biodata Wali Kelas
            WaliKelas::create([
                'user_id' => $user->id,
                'kelas_id' => $kelas->id,
                'nrg' => $nrg,
                'nip' => '1980' . rand(10000000000000, 99999999999999),
                'nama_lengkap' => $user->name,
                'jk' => ($index % 2 == 0) ? 'L' : 'P', 
                'no_hp' => '0812' . rand(10000000, 99999999),
                'alamat' => 'Jl. Pendidikan No. ' . rand(1, 100)
            ]);
        }
    }
}