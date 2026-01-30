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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            
            // Kode Transaksi: TRX2601180001
            $table->string('transaction_code')->unique();
            
            $table->date('transaction_date')->index();       // ✅ Tanggal transaksi (YYYY-MM-DD)
            $table->unsignedInteger('sequence_number');     // ✅ Nomor urut hari ini (1, 2, 3...)
            $table->unique(['transaction_date', 'sequence_number']); // ✅ Jamin unik per hari
            
            // Relasi
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ticket_id')->constrained('event_tickets')->cascadeOnDelete();
            
            // Detail Pembelian
            $table->integer('quantity')->default(1);
            $table->bigInteger('total_amount'); // dalam Rupiah (tanpa desimal)
            
            // Status Pembayaran
            $table->string('status')->default('pending'); // pending, success, failed, expired
            
            // PayLabs
            $table->string('payment_url')->nullable(); // URL redirect ke PayLabs
            $table->json('paylabs_response')->nullable(); // Simpan response dari PayLabs
            
            // Timestamps
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};