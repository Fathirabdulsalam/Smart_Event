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
        Schema::create('events', function (Blueprint $table) {
            $table->id();

            /* =====================
                BASIC EVENT
            ===================== */
            $table->string('name');
            $table->date('date');
            $table->date('end_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();

            /* =====================
                PRICE
            ===================== */
            $table->decimal('price', 15, 0)->default(0);
            $table->integer('discount_percentage')->default(0);

            /* =====================
                LOCATION TYPE
            ===================== */
            $table->enum('location_type', ['online', 'offline'])->default('offline');

            // ONLINE
            $table->string('online_link')->nullable();

            // OFFLINE
            $table->string('offline_place_name')->nullable();
            $table->text('offline_address')->nullable();
            $table->text('offline_maps_link')->nullable(); // <-- FIXED

            /* =====================
                RELATIONS (AMAN)
            ===================== */

            // WAJIB & AMAN
            $table->foreignId('author_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('category_id')
                ->constrained('categories')
                ->cascadeOnDelete();

            // MASTER (TANPA FK DULU)
            $table->unsignedBigInteger('master_type_id')->nullable();
            $table->unsignedBigInteger('master_event_kind_id')->nullable();
            $table->unsignedBigInteger('master_zone_id')->nullable();
            $table->unsignedBigInteger('master_ticket_category_id')->nullable();
            $table->unsignedBigInteger('master_subcategory_id')->nullable();
            $table->unsignedBigInteger('master_location_id')->nullable();

            /* =====================
                CONTENT
            ===================== */
            $table->text('details');
            $table->string('poster_path')->nullable();

            $table->enum('status', ['active', 'draft', 'ended'])
                ->default('active');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
