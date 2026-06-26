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
        Schema::create('wali_kelas', function (Blueprint $table) {
            $table->id();
            
            // --- 1. Relasi ---
            // Jika user dihapus, profil guru ikut terhapus (Ini benar)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // 🔥 PERBAIKAN: nullable() agar boleh kosong, dan nullOnDelete() agar guru tidak ikut terhapus jika kelas dihapus
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->nullOnDelete();
            
            // --- 2. Identitas Profesi ---
            $table->string('nrg', 12)->unique(); // 12 karakter untuk NRG
            $table->string('nip', 20)->unique()->nullable(); // 20 karakter untuk NIP (standar 18 digit)
            
            // --- 3. Biodata Diri ---
            $table->string('nama_lengkap', 35); // Wajib sinkron dengan tabel users
            $table->enum('jk', ['L', 'P']); // Jenis Kelamin
            $table->string('no_hp', 15); // 15 karakter untuk nomor HP
            $table->text('alamat')->nullable(); // Alamat Opsional
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wali_kelas');
    }
};