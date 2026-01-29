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
        Schema::table('events', function (Blueprint $table) {
            // Deskripsi & Ketentuan
            $table->text('description')->nullable();
            $table->text('terms')->nullable();

            // Info Kontak
            $table->string('contact_name')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();

            // Periode Penjualan Tiket
            $table->date('sale_start_date')->nullable();
            $table->date('sale_end_date')->nullable();
            $table->time('sale_start_time')->nullable();
            $table->time('sale_end_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
                'description',
                'terms',
                'contact_name',
                'contact_email',
                'contact_phone',
                'sale_start_date',
                'sale_end_date',
                'sale_start_time',
                'sale_end_time',
            ]);
        });
    }
};