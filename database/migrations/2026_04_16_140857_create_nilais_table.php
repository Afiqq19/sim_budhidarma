<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nilais', function (Blueprint $table) {
            $table->id();
            
            // Relasi Utama E-Rapor
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajarans')->onDelete('cascade');
            $table->foreignId('mapel_id')->constrained('mapels')->onDelete('cascade');
            
            // Komponen Nilai E-Rapor (Sudah Bersih & Optimal)
            $table->integer('nilai_pengetahuan')->nullable();
            $table->integer('nilai_keterampilan')->nullable();
            $table->integer('nilai_akhir')->default(0); 
            
            // Elemen Deskriptif E-Rapor
            // 2 karakter sangat presisi untuk A, B+, C, dll.
            $table->string('predikat', 2)->nullable(); 
            $table->text('deskripsi')->nullable();     
            $table->text('catatan_wali_kelas')->nullable(); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilais');
    }
};