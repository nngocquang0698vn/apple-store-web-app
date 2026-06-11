<?php

namespace App\Services;

use App\Models\ProductVariant;
use App\Support\CartSummaryPresenter;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class CartService
{
    private const SESSION_KEY = 'cart';

    public function __construct(private Session $session) {}

    /**
     * @return array<int, array{variant_id: int, quantity: int}>
     */
    public function rawItems(): array
    {
        /** @var array<int, array{variant_id: int, quantity: int}> $items */
        $items = $this->session->get(self::SESSION_KEY, []);

        return $items;
    }

    /**
     * @return Collection<int, array{
     *     variant: ProductVariant,
     *     quantity: int,
     *     unit_price: int,
     *     line_subtotal: int,
     *     is_purchasable: bool,
     * }>
     */
    public function getItems(): Collection
    {
        $rawItems = $this->rawItems();

        if ($rawItems === []) {
            return collect();
        }

        $variants = ProductVariant::query()
            ->with(['product.images', 'color', 'storageOption'])
            ->whereIn('id', array_keys($rawItems))
            ->get()
            ->keyBy('id');

        $lines = collect();

        foreach ($rawItems as $variantId => $item) {
            $variant = $variants->get($variantId);

            if ($variant === null) {
                continue;
            }

            $quantity = (int) $item['quantity'];
            $unitPrice = (int) $variant->sale_price;

            $lines->push([
                'variant' => $variant,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'line_subtotal' => $unitPrice * $quantity,
                'is_purchasable' => $this->isPurchasable($variant, $quantity),
            ]);
        }

        $this->persistSanitizedItems($lines);

        return $lines;
    }

    public function add(int $variantId, int $quantity): void
    {
        $variant = $this->resolvePurchasableVariant($variantId);
        $items = $this->rawItems();
        $newQuantity = ($items[$variantId]['quantity'] ?? 0) + $quantity;

        $this->assertWithinStock($variant, $newQuantity);

        $items[$variantId] = [
            'variant_id' => $variantId,
            'quantity' => $newQuantity,
        ];

        $this->session->put(self::SESSION_KEY, $items);
    }

    public function update(int $variantId, int $quantity): void
    {
        $items = $this->rawItems();

        if (! array_key_exists($variantId, $items)) {
            throw ValidationException::withMessages([
                'quantity' => ['Sản phẩm không có trong giỏ hàng.'],
            ]);
        }

        $variant = $this->resolvePurchasableVariant($variantId);
        $this->assertWithinStock($variant, $quantity);

        $items[$variantId]['quantity'] = $quantity;
        $this->session->put(self::SESSION_KEY, $items);
    }

    public function remove(int $variantId): void
    {
        $items = $this->rawItems();
        unset($items[$variantId]);
        $this->session->put(self::SESSION_KEY, $items);
    }

    public function clear(): void
    {
        $this->session->forget(self::SESSION_KEY);
    }

    public function count(): int
    {
        return (int) collect($this->rawItems())->sum('quantity');
    }

    public function subtotal(): int
    {
        return (int) $this->getItems()->sum('line_subtotal');
    }

    /**
     * @return array<string, mixed>
     */
    public function buildSummary(?int $variantId = null, ?int $quantity = null): array
    {
        return CartSummaryPresenter::fromCart($this, $variantId, $quantity);
    }

    public function hasPurchasabilityConflict(): bool
    {
        return $this->getItems()->contains(
            fn (array $line): bool => ! $line['is_purchasable']
        );
    }

    private function resolvePurchasableVariant(int $variantId): ProductVariant
    {
        $variant = ProductVariant::query()
            ->with('product')
            ->find($variantId);

        if ($variant === null) {
            throw ValidationException::withMessages([
                'variant_id' => ['Biến thể không tồn tại.'],
            ]);
        }

        if (! $variant->is_active) {
            throw ValidationException::withMessages([
                'variant_id' => ['Biến thể không khả dụng.'],
            ]);
        }

        $product = $variant->product;

        if ($product === null || ! $product->is_active || $product->trashed()) {
            throw ValidationException::withMessages([
                'variant_id' => ['Sản phẩm không khả dụng.'],
            ]);
        }

        return $variant;
    }

    private function assertWithinStock(ProductVariant $variant, int $quantity): void
    {
        if ($variant->stock_quantity <= 0) {
            throw ValidationException::withMessages([
                'quantity' => ['Sản phẩm đã hết hàng.'],
            ]);
        }

        if ($quantity > $variant->stock_quantity) {
            throw ValidationException::withMessages([
                'quantity' => ['Chỉ còn '.$variant->stock_quantity.' sản phẩm trong kho.'],
            ]);
        }
    }

    private function isPurchasable(ProductVariant $variant, int $quantity): bool
    {
        $product = $variant->product;

        if (! $variant->is_active || $product === null || ! $product->is_active || $product->trashed()) {
            return false;
        }

        return $quantity > 0 && $quantity <= $variant->stock_quantity;
    }

    /**
     * @param  Collection<int, array{variant: ProductVariant, quantity: int}>  $lines
     */
    private function persistSanitizedItems(Collection $lines): void
    {
        $sanitized = [];

        foreach ($lines as $line) {
            $variantId = $line['variant']->id;
            $sanitized[$variantId] = [
                'variant_id' => $variantId,
                'quantity' => (int) $line['quantity'],
            ];
        }

        $this->session->put(self::SESSION_KEY, $sanitized);
    }
}
