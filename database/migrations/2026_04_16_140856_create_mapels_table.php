<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mapels', function (Blueprint $table) {
            $table->id();
            // 10 karakter sangat cukup dan aman untuk kode (Misal: PAI, BING, PROD-TKJ)
            $table->string('kode_mapel', 10)->unique(); 
            // 50 karakter untuk mengantisipasi nama mapel produktif SMK yang panjang
            $table->string('nama_mapel', 100);          
            
            // Struktur Kurikulum SMK
            $table->enum('kelompok', ['A', 'B', 'C', 'C2', 'C3'])->default('A'); 
            
            // Fitur KKM 
            $table->integer('kkm')->default(75);    // Default 75
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mapels');
    }
};