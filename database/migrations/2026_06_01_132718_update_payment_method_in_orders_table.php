<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite doesn't support modifying enums directly, so we need to recreate the table
        Schema::table('orders', function (Blueprint $table) {
            // For SQLite, we need to drop and recreate
            $table->dropColumn('payment_method');
        });
        
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_method')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('payment_method');
        });
        
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('payment_method', ['cod', 'esewa', 'card'])->nullable();
        });
    }
};