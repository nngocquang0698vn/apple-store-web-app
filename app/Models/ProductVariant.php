<?php

namespace App\Models;

use Database\Factories\ProductVariantFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'product_id',
    'color_id',
    'storage_option_id',
    'sku',
    'original_price',
    'sale_price',
    'stock_quantity',
    'is_active',
])]
class ProductVariant extends Model
{
    /** @use HasFactory<ProductVariantFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'original_price' => 'integer',
            'sale_price' => 'integer',
            'stock_quantity' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }

    public function storageOption(): BelongsTo
    {
        return $this->belongsTo(StorageOption::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
