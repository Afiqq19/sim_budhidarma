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
        Schema::create('tahun_ajarans', function (Blueprint $table) {
            $table->id();
            // Format "2026/2027" memakan 9 karakter. Dibuat 10 agar presisi dan aman.
            $table->string('tahun', 10); 
            $table->enum('semester', ['Ganjil', 'Genap']);
            $table->boolean('is_active')->default(false); 
            $table->boolean('is_kenaikan_selesai')->default(false);
            
            // Kolom Master Harga SPP langsung masuk di sini dengan rapi
            $table->integer('nominal_spp')->default(250000); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cukup drop tabelnya saja, semua kolom di dalamnya otomatis lenyap
        Schema::dropIfExists('tahun_ajarans');
    }
};