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
        // Create the 'orders' table
        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();

                // Relationships
                $table->foreignId('user_id')->constrained()->onDelete('cascade');

                // Order Details
                $table->string('order_number')->unique()->index();
                $table->dateTime('order_date')->useCurrent();
                $table->enum('status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');

                // Pricing
                $table->decimal('subtotal', 10, 2);
                $table->decimal('tax', 10, 2)->default(0);
                $table->decimal('shipping_cost', 10, 2)->default(0);
                $table->decimal('total_amount', 10, 2);

                // Shipping Information
                $table->string('shipping_method');
                $table->text('shipping_address');
                $table->text('billing_address');

                // Payment Information
                $table->string('payment_method');
                $table->string('payment_status')->default('pending');
                $table->string('transaction_id')->nullable();

                // Timestamps
                $table->timestamps();
                $table->softDeletes();

                // Indexes
                $table->index(['user_id', 'status']);
                $table->index('order_date');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
