<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel jurusans (Wajib ditambahkan!)
            $table->foreignId('jurusan_id')->constrained('jurusans')->onDelete('cascade');
            
            // 20 karakter sangat aman untuk kode pendek (Misal: XII-TKJ-1)
            $table->string('kode_kelas', 20)->unique(); 
            // 50 karakter aman untuk nama kelas yang panjang
            $table->string('nama_kelas', 50); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};