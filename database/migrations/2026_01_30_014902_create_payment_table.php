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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('provider', 32)->nullable();
            $table->string('payment_type', 32)->nullable();

            $table->string('merchant_id', 64)->nullable();
            $table->string('request_id', 64)->nullable()->index();
            $table->string('merchant_trade_no', 64)->nullable()->index();
            $table->string('platform_trade_no', 64)->nullable()->index();

            $table->decimal('amount', 16, 2)->nullable();
            $table->string('product_name', 120)->nullable();

            $table->text('qr_code')->nullable();
            $table->text('qris_url')->nullable();

            $table->string('status', 16)->nullable()->index();
            $table->string('err_code', 64)->nullable();
            $table->string('err_code_des', 255)->nullable();

            $table->string('nmid', 64)->nullable();
            $table->string('rrn', 64)->nullable();
            $table->string('tid', 64)->nullable();
            $table->string('payer', 120)->nullable();
            $table->string('phone_number', 64)->nullable();
            $table->string('issuer_id', 64)->nullable();
            $table->string('expired_time', 64)->nullable();

            $table->json('raw_request')->nullable();
            $table->json('raw_response')->nullable();
            $table->json('notify_payload')->nullable();

            $table->timestamp('paid_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
