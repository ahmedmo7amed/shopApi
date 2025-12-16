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
        Schema::create('reservations', function (Blueprint $table) {
             $table->uuid('id')->primary();
        $table->unsignedBigInteger('product_id');
        $table->unsignedBigInteger('warehouse_id');
        $table->bigInteger('quantity');

        $table->uuid('order_id')->nullable();
        $table->timestamp('expires_at');
        $table->enum('status', ['ACTIVE', 'RELEASED', 'EXPIRED'])->default('ACTIVE');

        $table->timestamps();

        $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
