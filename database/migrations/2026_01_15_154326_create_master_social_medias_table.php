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
        Schema::create('master_social_medias', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // Contoh: Instagram
        $table->string('platform'); // Kunci: instagram, facebook, twitter, linkedin, youtube, tiktok
        $table->string('link_url'); // Contoh: https://instagram.com/...
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_social_medias');
    }
};
