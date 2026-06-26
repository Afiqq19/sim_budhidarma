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
        Schema::create('tagihan_spps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            // Relasi ke tahun ajaran agar tagihan SPP terikat pada tahun tertentu
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajarans')->onDelete('cascade');
            
            // 🔥 Optimasi: 20 karakter sudah sangat aman untuk nama bulan
            $table->string('bulan', 20); 
            $table->integer('nominal');
            $table->enum('status', ['Belum Lunas', 'Lunas'])->default('Belum Lunas');
            // 🔥 Optimasi: 50 karakter untuk token Midtrans (UUID biasanya 36 karakter)
            $table->string('snap_token', 50)->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tagihan_spps');
    }
};