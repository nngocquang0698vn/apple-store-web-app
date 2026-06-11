<?php

namespace App\Support;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class ProductImageOrdering
{
    /**
     * @return Collection<int, ProductImage>
     */
    public function ordered(Product|int $product): Collection
    {
        $productId = $product instanceof Product ? $product->id : $product;

        return ProductImage::query()
            ->where('product_id', $productId)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    public function normalize(Product|int $product): void
    {
        $images = $this->ordered($product);

        DB::transaction(function () use ($images): void {
            foreach ($images->values() as $position => $image) {
                $nextOrder = $position + 1;

                if ($image->sort_order !== $nextOrder) {
                    $image->update(['sort_order' => $nextOrder]);
                }
            }
        });
    }

    public function move(ProductImage $image, string $direction): bool
    {
        $product = $image->product;
        $this->normalize($product);
        $image->refresh();

        $images = $this->ordered($product)->values();
        $currentIndex = $images->search(fn (ProductImage $item): bool => $item->id === $image->id);

        if ($currentIndex === false) {
            return false;
        }

        $targetIndex = $direction === 'up'
            ? $currentIndex - 1
            : $currentIndex + 1;

        if ($targetIndex < 0 || $targetIndex >= $images->count()) {
            return false;
        }

        $reordered = $images->all();
        $temporary = $reordered[$currentIndex];
        $reordered[$currentIndex] = $reordered[$targetIndex];
        $reordered[$targetIndex] = $temporary;

        DB::transaction(function () use ($reordered): void {
            foreach ($reordered as $position => $item) {
                $item->update(['sort_order' => $position + 1]);
            }
        });

        return true;
    }
}
