<?php

namespace App\Models;

use Database\Factories\ProductSeriesFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['category_id', 'name', 'slug', 'release_year', 'is_active', 'sort_order'])]
class ProductSeries extends Model
{
    /** @use HasFactory<ProductSeriesFactory> */
    use HasFactory;

    protected $table = 'product_series';

    protected function casts(): array
    {
        return [
            'release_year' => 'integer',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
