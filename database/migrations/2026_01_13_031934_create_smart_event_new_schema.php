<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /* =====================
            UPDATE USERS
        ===================== */
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['super_admin', 'admin', 'user', 'creator'])
                    ->default('user')
                    ->after('email');
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable();
            }
            if (!Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable();
            }
            if (!Schema::hasColumn('users', 'photo_path')) {
                $table->string('photo_path')->nullable();
            }
        });

        /* =====================
            MASTER TABLES
        ===================== */
        Schema::create('master_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('master_subcategories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('master_event_kinds', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('master_zones', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('gmt_offset');
            $table->timestamps();
        });

        Schema::create('master_locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->timestamps();
        });

        Schema::create('master_ticket_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_paid')->default(true);
            $table->timestamps();
        });

        Schema::create('master_slides', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('image_path');
            $table->string('link_url')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        /* =====================
            ARTICLES
        ===================== */
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('content');
            $table->string('thumbnail_path')->nullable();
            $table->foreignId('author_id')->constrained('users');
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamps();
        });

        /* =====================
            CONFIG
        ===================== */
        Schema::create('configurations', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('value')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configurations');
        Schema::dropIfExists('articles');
        Schema::dropIfExists('master_slides');
        Schema::dropIfExists('master_ticket_categories');
        Schema::dropIfExists('master_locations');
        Schema::dropIfExists('master_zones');
        Schema::dropIfExists('master_event_kinds');
        Schema::dropIfExists('master_subcategories');
        Schema::dropIfExists('master_types');
    }
};
