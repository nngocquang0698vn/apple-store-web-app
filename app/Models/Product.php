<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'category_id',
    'product_series_id',
    'name',
    'slug',
    'short_description',
    'description',
    'specifications',
    'release_year',
    'is_featured',
    'is_active',
])]
class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'release_year' => 'integer',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function productSeries(): BelongsTo
    {
        return $this->belongsTo(ProductSeries::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function resolveRouteBinding($value, $field = null): ?static
    {
        $field = $field ?: $this->getRouteKeyName();

        return static::query()
            ->where($field, $value)
            ->where('is_active', true)
            ->firstOrFail();
    }

    /**
     * @param  Builder<Product>  $query
     * @return Builder<Product>
     */
    public function scopeCatalogOrder(Builder $query): Builder
    {
        $table = $query->getModel()->getTable();
        $joins = $query->getQuery()->joins ?? [];
        $hasCategoryJoin = collect($joins)->contains(
            static fn ($join): bool => ($join->table ?? '') === 'categories',
        );

        if (! $hasCategoryJoin) {
            $query->join('categories', "{$table}.category_id", '=', 'categories.id');
        }

        $columns = $query->getQuery()->columns;

        if ($columns === null || $columns === ['*']) {
            $query->select("{$table}.*");
        }

        return $query
            ->orderBy('categories.sort_order')
            ->orderByDesc("{$table}.is_featured")
            ->orderByDesc("{$table}.release_year")
            ->orderBy("{$table}.name");
    }
}
