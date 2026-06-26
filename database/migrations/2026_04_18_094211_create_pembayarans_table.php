<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            // 50 karakter aman untuk Order ID dari Midtrans
            $table->string('order_id', 50)->unique(); 
            
            // Relasi Utama (Siswa dan Tagihan SPP Bapak yang sudah ada)
            $table->foreignId('tagihan_spp_id')->constrained('tagihan_spps')->onDelete('cascade');
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            
            // Siapa yang menerima kalau bayar manual? (Akan NULL kalau bayar otomatis via Midtrans)
            $table->foreignId('pegawai_id')->nullable()->constrained('pegawais'); 

            $table->date('tanggal_bayar')->nullable(); // Kapan uangnya benar-benar masuk
            $table->integer('jumlah_bayar'); // Berapa nominal yang dibayar
            
            // Kolom Status & Metode Pembayaran (Siap untuk Midtrans VA maupun Manual)
            // 50 karakter cukup untuk nama metode pembayaran
            $table->string('metode_pembayaran', 50)->nullable(); 
            $table->enum('status_bayar', ['pending', 'success', 'failed', 'expire'])->default('pending');
            
            // 50 karakter aman untuk Snap Token Midtrans (biasanya 36 karakter)
            $table->string('snap_token', 50)->nullable(); 
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pembayarans');
    }
};