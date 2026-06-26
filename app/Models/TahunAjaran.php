<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    use HasFactory;

    // Tambahkan baris ini untuk membuka gembok keamanan Laravel
    protected $guarded = [];
    // Relasi dengan Nilai
    public function nilais()
    {
        return $this->hasMany(Nilai::class);        
    }
}