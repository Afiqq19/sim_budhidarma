<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    // Membuka gembok keamanan untuk semua kolom
    protected $guarded = [];

    // BUMBU RAHASIA: Relasi ke tabel Jurusan (BelongsTo = Milik Dari)
    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id');
    }
    

    // Relasi Many-to-Many ke tabel Mapel melalui kelas_mapel
    public function mapels()
    {
        return $this->belongsToMany(Mapel::class, 'kelas_mapel', 'kelas_id', 'mapel_id');
    }

    // Tambahkan relasi ini agar Kelas tahu siapa saja siswanya (HasMany)
    public function siswas()
    {
        return $this->hasMany(Siswa::class);
    }
    
}