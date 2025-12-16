<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
             $table->unsignedBigInteger('product_id');
        $table->unsignedBigInteger('warehouse_id');

        $table->bigInteger('quantity_on_hand')->default(0);
        $table->bigInteger('quantity_reserved')->default(0);
        $table->bigInteger('version')->default(1); // optimistic locking

        $table->primary(['product_id','warehouse_id']);
        $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
        $table->foreign('warehouse_id')->references('id')->on('warehouses')->cascadeOnDelete();
         $table->text('note')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
