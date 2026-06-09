<?php

use App\Enums\OrderStatus;
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
        Schema::create('orders', function (Blueprint $table): void {
            $table->id();
            $table->string('order_code', 30)->unique();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->string('receiver_name', 100);
            $table->string('receiver_phone', 20);
            $table->string('province', 100);
            $table->string('district', 100);
            $table->string('ward', 100);
            $table->string('address_line', 255);
            $table->string('note', 500)->nullable();
            $table->string('payment_method', 20)->default('cod');
            $table->unsignedBigInteger('subtotal');
            $table->unsignedBigInteger('shipping_fee')->default(0);
            $table->unsignedBigInteger('total_amount');
            $table->string('status', 20)->default(OrderStatus::Pending->value);
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('order_code');
            $table->index('status');
            $table->index('created_at');
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
