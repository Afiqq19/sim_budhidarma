<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;
    protected $guarded = [];

    // Relasi ke tabel users (Akun Login)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}