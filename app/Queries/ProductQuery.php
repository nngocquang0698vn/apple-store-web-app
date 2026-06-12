<?php

namespace App\Queries;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

final class ProductQuery
{
    public const PER_PAGE = 12;

    public const PER_PAGE_MOBILE = 3;

    public const SORT_OPTIONS = [
        'catalog',
        'newest',
        'price_asc',
        'price_desc',
        'name_asc',
        'name_desc',
    ];

    /**
     * @param  array<string, mixed>  $filters
     */
    public function paginate(array $filters): LengthAwarePaginator
    {
        $sort = $this->resolveSort($filters['sort'] ?? null);

        $query = Product::query()
            ->where('products.is_active', true)
            ->whereHas('variants', fn (Builder $variantQuery) => $this->applyVariantFilters($variantQuery, $filters));

        $this->applySearch($query, $filters);
        $this->applyCategoryFilter($query, $filters);
        $this->applySeriesFilter($query, $filters);
        $this->applyFeaturedFilter($query, $filters);

        $activeVariants = fn (Builder $variantQuery): Builder => $variantQuery->where('is_active', true);

        $query
            ->with([
                'category',
                'productSeries',
                'images' => fn ($imageQuery) => $imageQuery
                    ->orderByDesc('is_primary')
                    ->orderBy('sort_order')
                    ->limit(1),
            ])
            ->withMin(['variants' => $activeVariants], 'sale_price')
            ->withMax(['variants' => $activeVariants], 'sale_price')
            ->withExists([
                'variants as has_stock' => fn (Builder $variantQuery) => $variantQuery
                    ->where('is_active', true)
                    ->where('stock_quantity', '>', 0),
            ]);

        $this->applySort($query, $sort);

        $perPage = (int) ($filters['per_page'] ?? self::PER_PAGE);

        if (! in_array($perPage, [self::PER_PAGE_MOBILE, self::PER_PAGE], true)) {
            $perPage = self::PER_PAGE;
        }

        return $query
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    private function applySearch(Builder $query, array $filters): void
    {
        $keyword = trim((string) ($filters['q'] ?? ''));

        if ($keyword === '') {
            return;
        }

        $term = '%'.$keyword.'%';

        $query->where(function (Builder $searchQuery) use ($term): void {
            $searchQuery
                ->where('products.name', 'like', $term)
                ->orWhere('products.short_description', 'like', $term)
                ->orWhereHas('category', fn (Builder $categoryQuery) => $categoryQuery
                    ->where('is_active', true)
                    ->where('name', 'like', $term))
                ->orWhereHas('productSeries', fn (Builder $seriesQuery) => $seriesQuery
                    ->where('is_active', true)
                    ->where('name', 'like', $term))
                ->orWhereHas('variants', fn (Builder $variantQuery) => $variantQuery
                    ->where('is_active', true)
                    ->where('sku', 'like', $term));
        });
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    private function applyCategoryFilter(Builder $query, array $filters): void
    {
        $categorySlug = $filters['category'] ?? null;

        if (! is_string($categorySlug) || $categorySlug === '') {
            return;
        }

        $query->whereHas('category', fn (Builder $categoryQuery) => $categoryQuery
            ->where('slug', $categorySlug)
            ->where('is_active', true));
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    private function applySeriesFilter(Builder $query, array $filters): void
    {
        $seriesSlug = $filters['series'] ?? null;

        if (! is_string($seriesSlug) || $seriesSlug === '') {
            return;
        }

        $query->whereHas('productSeries', fn (Builder $seriesQuery) => $seriesQuery
            ->where('slug', $seriesSlug)
            ->where('is_active', true));
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    private function applyFeaturedFilter(Builder $query, array $filters): void
    {
        if (! empty($filters['featured'])) {
            $query->where('products.is_featured', true);
        }
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    private function applyVariantFilters(Builder $query, array $filters): void
    {
        $query->where('is_active', true);

        $colors = array_values(array_filter((array) ($filters['colors'] ?? [])));
        if ($colors !== []) {
            $query->whereHas('color', fn (Builder $colorQuery) => $colorQuery
                ->whereIn('slug', $colors)
                ->where('is_active', true));
        }

        $storages = array_values(array_filter((array) ($filters['storages'] ?? []), fn ($value) => $value !== null && $value !== ''));
        if ($storages !== []) {
            $query->whereHas('storageOption', fn (Builder $storageQuery) => $storageQuery
                ->whereIn('capacity_gb', $storages)
                ->where('is_active', true));
        }

        if (isset($filters['min_price'])) {
            $query->where('sale_price', '>=', (int) $filters['min_price']);
        }

        if (isset($filters['max_price'])) {
            $query->where('sale_price', '<=', (int) $filters['max_price']);
        }

        if (! empty($filters['in_stock'])) {
            $query->where('stock_quantity', '>', 0);
        }
    }

    private function applySort(Builder $query, string $sort): void
    {
        match ($sort) {
            'newest' => $query->orderByDesc('products.created_at'),
            'price_asc' => $query->orderBy('variants_min_sale_price'),
            'price_desc' => $query->orderByDesc('variants_min_sale_price'),
            'name_asc' => $query->orderBy('products.name'),
            'name_desc' => $query->orderByDesc('products.name'),
            default => $query->catalogOrder(),
        };
    }

    private function resolveSort(?string $sort): string
    {
        if ($sort !== null && in_array($sort, self::SORT_OPTIONS, true)) {
            return $sort;
        }

        return 'catalog';
    }
}
