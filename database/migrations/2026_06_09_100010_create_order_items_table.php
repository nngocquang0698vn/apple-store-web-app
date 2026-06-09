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
        Schema::create('order_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained()->nullOnDelete();
            $table->string('product_name', 160);
            $table->string('sku', 60);
            $table->string('color_name', 60);
            $table->string('storage_label', 30);
            $table->unsignedBigInteger('unit_price');
            $table->unsignedInteger('quantity');
            $table->unsignedBigInteger('line_total');
            $table->timestamps();

            $table->index('order_id');
            $table->index('product_id');
            $table->index('product_variant_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
