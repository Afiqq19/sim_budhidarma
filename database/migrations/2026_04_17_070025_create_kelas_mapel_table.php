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
        Schema::create('kelas_mapel', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel kelas
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            // Menghubungkan ke tabel mapels
            $table->foreignId('mapel_id')->constrained('mapels')->onDelete('cascade');
            
            // 🔥 OPTIMASI PRO: Mencegah duplikasi mata pelajaran di kelas yang sama
            $table->unique(['kelas_id', 'mapel_id']);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas_mapel');
    }
};