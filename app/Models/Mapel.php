<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    use HasFactory;

    // Buka gembok keamanan mass-assignment agar semua kolom bisa diisi
    protected $guarded = []; 
    
    public function kelases()
    {
        return $this->belongsToMany(Kelas::class, 'kelas_mapel');
    }
}
