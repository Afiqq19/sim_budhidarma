<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagihanSpp extends Model
{
    // INI KUNCI UTAMANYA: Membuka gembok agar data bisa disimpan massal
    protected $guarded = [];

    // Sekalian kita buatkan relasinya agar nanti laporannya rapi
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function tahun_ajaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }
}