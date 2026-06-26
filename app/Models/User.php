<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// DI SINI PERUBAHANNYA: Kita tambahkan 'username' dan 'role' di dalam atribut Fillable
#[Fillable(['name', 'email', 'username', 'role', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // [1] Relasi dari User ke Pegawai (Untuk Admin & Bendahara)
    public function pegawai()
    {
        return $this->hasOne(Pegawai::class);
    }

    // [2] Relasi dari User ke Siswa (Nanti berguna saat Siswa login untuk bayar via VA)
    public function siswa()
    {
        return $this->hasOne(Siswa::class);
    }

    // [3] Relasi dari User ke Wali Kelas (Nanti berguna untuk laporan wali kelas)
        public function waliKelas()
    {
        return $this->hasOne(WaliKelas::class, 'user_id');
    }
}