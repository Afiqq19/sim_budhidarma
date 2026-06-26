<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    use HasFactory;
    // Sebutkan satu per satu nama kolom yang BOLEH diisi dari form
    protected $fillable = [
        'kode_jurusan', 
        'nama_jurusan'
    ]; 
}