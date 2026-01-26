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
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            // Relasi ke User (Calon Author)
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // Karena belum ada Event, Kategori ini melekat pada Author (Spesialisasi)
            // Bisa menggunakan ID jika tabel categories sudah ada, atau string dulu.
            // Di sini saya asumsi pakai ID karena fitur Category sudah anda buat sebelumnya.
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            
            // Status pendaftaran author
            $table->enum('status', ['active', 'pending', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
