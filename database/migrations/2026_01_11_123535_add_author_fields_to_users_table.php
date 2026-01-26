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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            
            $table->string('username')->unique()->nullable()->after('name');
            $table->string('phone_number')->nullable()->after('email');
            
            $table->string('photo_path')->nullable();
            $table->string('ktp_path')->nullable();
            $table->string('npwp_path')->nullable();
            
            $table->text('address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn([
                'category_id', 
                'username', 
                'phone_number',
                'photo_path', 
                'ktp_path', 
                'npwp_path', 
                'address', 
            ]);
        });
    }
};
