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
        Schema::create('products', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->foreignId('product_series_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name', 160);
            $table->string('slug', 180)->unique();
            $table->string('short_description', 500)->nullable();
            $table->longText('description')->nullable();
            $table->longText('specifications')->nullable();
            $table->unsignedSmallInteger('release_year')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('category_id');
            $table->index('product_series_id');
            $table->index('release_year');
            $table->index(['is_active', 'is_featured']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
