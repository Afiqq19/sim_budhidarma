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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 35); // Dibuat 35 karakter agar lebih rasional
            $table->string('username', 35)->unique(); 
            $table->string('password'); // Tetap default untuk enkripsi bcrypt
            // Kunci 5 Role Sistem Kita:
            $table->enum('role', ['admin','tu', 'bendahara', 'wali_kelas', 'siswa']);
            $table->string('email', 50)->unique()->nullable(); // Dibuat 50 karakter
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            // Harus sama dengan panjang email di tabel users
            $table->string('email', 50)->primary(); 
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};