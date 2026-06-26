<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaliKelas extends Model
{
    use HasFactory;

    // 🔥 PENTING: Beri tahu Laravel nama tabel pastinya di database
    protected $table = 'wali_kelas'; 

    // Daftarkan kolom-kolom yang diizinkan untuk diisi dari form
    protected $fillable = [
        'user_id', 
        'kelas_id',
        'nrg', 
        'nip', 
        'nama_lengkap', 
        'jk', 
        'no_hp', 
        'alamat'
    ];

    // ==========================================
    // RELASI ANTAR TABEL
    // ==========================================

    // Relasi ke Akun Login (Tabel users)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Kelas yang dipegangnya (Tabel kelas)
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}