<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Konfigurasi Transaksi
            $table->integer('max_ticket_per_trx')->default(5);
            $table->boolean('one_email_one_trx')->default(true);
            $table->boolean('one_ticket_one_person')->default(true);
            
            // Sosial Media
            $table->string('sosmed_instagram')->nullable();
            $table->string('sosmed_twitter')->nullable();
            $table->string('sosmed_linkedin')->nullable();
            $table->string('sosmed_facebook')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
                'max_ticket_per_trx',
                'one_email_one_trx',
                'one_ticket_one_person',
                'sosmed_instagram',
                'sosmed_twitter',
                'sosmed_linkedin',
                'sosmed_facebook'
            ]);
        });
    }
};