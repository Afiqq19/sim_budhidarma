<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $guarded = [];

    // Relasi ke Siswa yang membayar
    public function siswa() {
        return $this->belongsTo(Siswa::class);
    }
    
    // Relasi ke Bendahara/Pegawai yang menerima uang
    public function pegawai() {
        return $this->belongsTo(Pegawai::class); 
    }

    // INI YANG BARU: Relasi ke Tagihan/Faktur SPP-nya
    public function tagihan_spp() {
        return $this->belongsTo(TagihanSpp::class);
    }
}