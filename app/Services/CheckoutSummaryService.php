<?php

namespace App\Services;

use App\Models\ProductVariant;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class CheckoutSummaryService
{
    public function __construct(private CartService $cart) {}

    /**
     * @return array{
     *     cart_count: int,
     *     items: list<array<string, mixed>>,
     *     cart_subtotal: int,
     *     shipping_fee: int,
     *     grand_total: int,
     *     can_checkout: bool,
     *     warnings: list<string>,
     * }
     */
    public function build(): array
    {
        $lines = $this->cart->getItems();

        if ($lines->isEmpty()) {
            throw ValidationException::withMessages([
                'cart' => ['Giỏ hàng trống.'],
            ]);
        }

        $items = $lines->map(function (array $line): array {
            $variant = $line['variant'];
            $product = $variant->product;

            return [
                'variant_id' => $variant->id,
                'product_name' => $product?->name ?? 'Sản phẩm không còn tồn tại',
                'sku' => $variant->sku,
                'color_name' => $variant->color?->name ?? '',
                'storage_label' => $variant->storageOption?->label ?? '',
                'quantity' => $line['quantity'],
                'unit_price' => $line['unit_price'],
                'line_subtotal' => $line['line_subtotal'],
                'stock_quantity' => $variant->stock_quantity,
                'is_purchasable' => $line['is_purchasable'],
            ];
        })->values()->all();

        $warnings = $this->buildWarnings($lines);
        $canCheckout = $lines->every(fn (array $line): bool => $line['is_purchasable']);
        $subtotal = (int) $lines->sum('line_subtotal');
        $shippingFee = ShippingFeeCalculator::calculate($subtotal);

        return [
            'cart_count' => $this->cart->count(),
            'items' => $items,
            'cart_subtotal' => $subtotal,
            'shipping_fee' => $shippingFee,
            'grand_total' => $subtotal + $shippingFee,
            'can_checkout' => $canCheckout,
            'warnings' => $warnings,
        ];
    }

    public function hasConflict(): bool
    {
        return $this->cart->getItems()->contains(
            fn (array $line): bool => ! $line['is_purchasable']
        );
    }

    /**
     * @param  Collection<int, array{variant: ProductVariant, quantity: int, is_purchasable: bool}>  $lines
     * @return list<string>
     */
    private function buildWarnings(Collection $lines): array
    {
        $warnings = [];

        foreach ($lines as $line) {
            $variant = $line['variant'];
            $product = $variant->product;
            $label = $product?->name ?? $variant->sku;

            if (! $line['is_purchasable']) {
                if ($variant->stock_quantity <= 0) {
                    $warnings[] = "{$label} đã hết hàng.";
                } elseif ($line['quantity'] > $variant->stock_quantity) {
                    $warnings[] = "{$label} chỉ còn {$variant->stock_quantity} sản phẩm trong kho.";
                } elseif (! $variant->is_active || $product === null || ! $product->is_active || $product->trashed()) {
                    $warnings[] = "{$label} hiện không khả dụng.";
                } else {
                    $warnings[] = "{$label} không thể thanh toán.";
                }
            }
        }

        return $warnings;
    }
}
