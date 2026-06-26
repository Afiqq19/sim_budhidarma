<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    // Gembok kita buka supaya gampang input
    protected $guarded = [];

    // Relasi: Siswa ini punya siapa akun login-nya?
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi: Siswa ini duduk di kelas mana sekarang?
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
    public function nilais() {
        return $this->hasMany(Nilai::class);
    }
    
}