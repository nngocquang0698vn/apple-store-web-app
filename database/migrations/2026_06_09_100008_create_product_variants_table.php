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
        Schema::create('product_variants', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->foreignId('color_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('storage_option_id')->nullable()->constrained()->nullOnDelete();
            $table->string('sku', 60)->unique();
            $table->unsignedBigInteger('original_price')->nullable();
            $table->unsignedBigInteger('sale_price');
            $table->unsignedInteger('stock_quantity')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('product_id');
            $table->index('color_id');
            $table->index('storage_option_id');
            $table->index('sale_price');
            $table->index(['is_active', 'stock_quantity']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
