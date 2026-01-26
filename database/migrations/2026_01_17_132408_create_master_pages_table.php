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
        Schema::create('master_pages', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Judul Halaman (misal: Syarat & Ketentuan)
            $table->string('slug')->unique(); // URL (misal: terms, privacy, refund)
            $table->longText('content'); // Isi konten (HTML)
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_pages');
    }
};
