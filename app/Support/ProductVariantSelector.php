<?php

namespace App\Support;

use App\Models\Color;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\StorageOption;
use Illuminate\Support\Collection;

final class ProductVariantSelector
{
    /** @var Collection<int, ProductVariant> */
    private Collection $variants;

    public function __construct(Product $product)
    {
        $this->variants = $product->variants
            ->where('is_active', true)
            ->values();
    }

    public function hasVariants(): bool
    {
        return $this->variants->isNotEmpty();
    }

    /**
     * @return Collection<int, Color>
     */
    public function colors(): Collection
    {
        return $this->variants
            ->pluck('color')
            ->filter()
            ->unique('id')
            ->sortBy('sort_order')
            ->values();
    }

    /**
     * @return Collection<int, StorageOption>
     */
    public function storagesForColor(?int $colorId): Collection
    {
        return $this->variants
            ->when($colorId, fn (Collection $collection) => $collection->where('color_id', $colorId))
            ->pluck('storageOption')
            ->filter()
            ->unique('id')
            ->sortBy('sort_order')
            ->values();
    }

    public function resolve(?string $colorSlug, ?int $storageCapacityGb): ?ProductVariant
    {
        if ($this->variants->isEmpty()) {
            return null;
        }

        $candidates = $this->variants;

        if ($colorSlug !== null && $colorSlug !== '') {
            $colorId = $this->variants
                ->first(fn (ProductVariant $variant) => $variant->color?->slug === $colorSlug)
                ?->color_id;

            if ($colorId === null) {
                return $this->defaultVariant();
            }

            $candidates = $candidates->where('color_id', $colorId);
        }

        if ($storageCapacityGb !== null) {
            $candidates = $candidates->filter(
                fn (ProductVariant $variant) => $variant->storageOption?->capacity_gb === $storageCapacityGb
            );
        }

        return $candidates->first() ?? $this->defaultVariant();
    }

    public function defaultVariant(): ?ProductVariant
    {
        return $this->variants
            ->sortByDesc(fn (ProductVariant $variant) => $variant->stock_quantity > 0)
            ->sortByDesc('stock_quantity')
            ->first();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function toClientPayload(Product $product, ?string $defaultImageUrl = null): array
    {
        /** @var Collection<int, ProductImage> $images */
        $images = $product->relationLoaded('images')
            ? $product->images
            : $product->images()->orderBy('sort_order')->orderBy('id')->get();

        return $this->variants
            ->map(fn (ProductVariant $variant) => [
                'id' => $variant->id,
                'sku' => $variant->sku,
                'color_slug' => $variant->color?->slug,
                'color_name' => $variant->color?->name,
                'storage_gb' => $variant->storageOption?->capacity_gb,
                'storage_label' => $variant->storageOption?->label,
                'sale_price' => $variant->sale_price,
                'original_price' => $variant->original_price,
                'stock_quantity' => $variant->stock_quantity,
                'image_url' => self::resolveVariantImageUrl($product->slug, $variant->color?->slug, $images)
                    ?? $defaultImageUrl,
            ])
            ->values()
            ->all();
    }

    /**
     * @param  Collection<int, ProductImage>  $images
     */
    public static function resolveVariantImageUrl(
        string $productSlug,
        ?string $colorSlug,
        Collection $images,
    ): ?string {
        if ($colorSlug === null || $colorSlug === '') {
            return null;
        }

        $suffix = "{$productSlug}-{$colorSlug}.webp";

        $match = $images->first(
            fn (ProductImage $image): bool => str_ends_with($image->path, $suffix),
        );

        return $match !== null ? ProductImageUrl::resolve($match->path) : null;
    }

    public function hasCombination(?string $colorSlug, ?int $storageCapacityGb): bool
    {
        if ($colorSlug === null || $storageCapacityGb === null) {
            return true;
        }

        return $this->variants->contains(
            fn (ProductVariant $variant): bool => $variant->color?->slug === $colorSlug
                && $variant->storageOption?->capacity_gb === $storageCapacityGb
        );
    }
}
