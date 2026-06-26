<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('pegawais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // 20 karakter untuk NIP
            $table->string('nip', 20)->nullable(); 
            // 35 karakter wajib sinkron dengan tabel users
            $table->string('nama_lengkap', 35);
            $table->enum('jk', ['L', 'P']);
            // 15 karakter untuk nomor HP
            $table->string('no_hp', 15)->nullable();
            $table->text('alamat')->nullable();
            // 40 karakter sangat cukup untuk nama jabatan
            $table->string('jabatan', 40); 
            
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('pegawais');
    }
};