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
        Schema::create('siswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade'); 
            
            // NISN pasti 10 digit
            $table->string('nisn', 10)->unique();
            // 40 karakter wajib sinkron dengan tabel users
            $table->string('nama_lengkap', 35);
            $table->enum('jk', ['L', 'P']);
            // 30 karakter cukup untuk nama kota terpanjang
            $table->string('tempat_lahir', 30)->nullable();
            $table->date('tanggal_lahir')->nullable();
            // Text tidak perlu dibatasi karena alamat bisa bervariasi panjangnya
            $table->text('alamat')->nullable();
            // 15 karakter aman untuk nomor HP
            $table->string('no_hp_siswa', 15)->nullable();
            $table->string('nama_orang_tua', 35)->nullable();
            $table->string('no_hp_ortu', 15)->nullable();
            
            // TAMBAHAN BARU UNTUK ALUMNI:
            $table->enum('status_siswa', ['Aktif', 'Pindah', 'Alumni'])->default('Aktif');
            // string 4 karakter sudah sangat tepat dari awal untuk tahun
            $table->string('tahun_lulus', 4)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};