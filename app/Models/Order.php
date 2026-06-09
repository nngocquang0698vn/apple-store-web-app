<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'order_code',
    'user_id',
    'receiver_name',
    'receiver_phone',
    'province',
    'district',
    'ward',
    'address_line',
    'note',
    'payment_method',
    'subtotal',
    'shipping_fee',
    'total_amount',
    'status',
    'cancelled_at',
    'completed_at',
])]
class Order extends Model
{
    /** @use HasFactory<OrderFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'subtotal' => 'integer',
            'shipping_fee' => 'integer',
            'total_amount' => 'integer',
            'status' => OrderStatus::class,
            'cancelled_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
