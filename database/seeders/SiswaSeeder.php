<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class SiswaSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID'); 
        $kelases = Kelas::all();

        foreach ($kelases as $kelas) {
            // Buat 5 siswa untuk setiap kelas
            for ($i = 1; $i <= 5; $i++) {
                
                $nisn = $faker->unique()->numerify('00########'); 
                $nama_siswa = $faker->name();

                // 1. Buat Akun User (Siswa)
                $user = User::create([
                    'name' => $nama_siswa,
                    'username' => $nisn, 
                    'email' => $nisn . '@siswa.com', 
                    'password' => Hash::make('password123'), 
                    'role' => 'siswa' 
                ]);

                // 2. Buat Data Biodata Siswa
                Siswa::create([
                    'user_id' => $user->id,
                    'kelas_id' => $kelas->id,
                    'nisn' => $nisn,
                    'nama_lengkap' => $nama_siswa,
                    'jk' => $faker->randomElement(['L', 'P']),
                    'tempat_lahir' => $faker->city(),
                    'tanggal_lahir' => $faker->date('Y-m-d', '2008-12-31'), 
                    'alamat' => $faker->address(),
                    'nama_orang_tua' => $faker->name(),
                    'no_hp_ortu' => $faker->numerify('0822########'),
                    // INI YANG DITAMBAHKAN AGAR SEEDER MENGISI NO HP SISWA
                    'no_hp_siswa' => $faker->numerify('0812########'), 
                ]);
            }
        }
    }
}