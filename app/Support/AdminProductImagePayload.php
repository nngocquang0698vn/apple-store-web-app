<?php

namespace App\Support;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Collection;

final class AdminProductImagePayload
{
    /**
     * @return list<array<string, mixed>>
     */
    public static function forProduct(Product|int $product): array
    {
        $productId = $product instanceof Product ? $product->id : $product;

        app(ProductImageOrdering::class)->normalize($productId);

        return ProductImage::query()
            ->where('product_id', $productId)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(fn (ProductImage $image): array => self::forImage($image))
            ->all();
    }

    /**
     * @return array<string, mixed>
     */
    public static function forImage(ProductImage $image): array
    {
        return [
            'id' => $image->id,
            'url' => ProductImageUrl::resolve($image->path) ?? '',
            'alt_text' => $image->alt_text ?? '',
            'sort_order' => $image->sort_order,
            'is_primary' => $image->is_primary,
        ];
    }

    /**
     * @param  Collection<int, ProductImage>  $images
     * @return list<array<string, mixed>>
     */
    public static function forCollection(Collection $images): array
    {
        return $images
            ->map(fn (ProductImage $image): array => self::forImage($image))
            ->values()
            ->all();
    }
}
