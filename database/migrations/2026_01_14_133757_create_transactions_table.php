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
            // Relasi ke Registration (Pesanan Tiket)
            $table->foreignId('registration_id')->constrained('registrations')->cascadeOnDelete();
            
            $table->string('external_id')->unique(); // ID unik untuk Xendit
            $table->string('payment_channel')->nullable(); // BCA, OVO, dll
            $table->string('payment_method')->nullable();  // VIRTUAL_ACCOUNT, E_WALLET
            $table->string('payment_status')->default('PENDING'); // PENDING, PAID, EXPIRED, FAILED
            $table->decimal('amount', 15, 2);
            $table->decimal('total_paid', 15, 2)->default(0);
            $table->string('checkout_link')->nullable(); // Link pembayaran Xendit
            
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expiry_date')->nullable();
            
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
