<?php

namespace App\Support;

use App\Models\ProductVariant;
use App\Services\CartService;
use App\Services\ShippingFeeCalculator;
use Illuminate\Support\Collection;

final class CartSummaryPresenter
{
    /**
     * @return array<string, mixed>
     */
    public static function fromCart(CartService $cart, ?int $focusVariantId = null, ?int $focusQuantity = null): array
    {
        $lines = $cart->getItems();
        $subtotal = (int) $lines->sum('line_subtotal');
        $shippingFee = ShippingFeeCalculator::calculate($subtotal);
        $grandTotal = $subtotal + $shippingFee;

        $data = [
            'cart_count' => $cart->count(),
            'cart_subtotal' => $subtotal,
            'shipping_fee' => $shippingFee,
            'grand_total' => $grandTotal,
            'items' => self::presentItems($lines),
            'formatted' => [
                'cart_subtotal' => VndMoney::format($subtotal),
                'shipping_fee' => VndMoney::format($shippingFee),
                'grand_total' => VndMoney::format($grandTotal),
            ],
        ];

        if ($focusVariantId === null) {
            return $data;
        }

        $variant = ProductVariant::query()->find($focusVariantId);

        if ($variant === null) {
            return $data;
        }

        $lineQuantity = $focusQuantity ?? ($cart->rawItems()[$focusVariantId]['quantity'] ?? null);

        if ($lineQuantity === null) {
            return $data;
        }

        $unitPrice = (int) $variant->sale_price;
        $lineSubtotal = $unitPrice * (int) $lineQuantity;

        return array_merge($data, [
            'variant_id' => $focusVariantId,
            'quantity' => (int) $lineQuantity,
            'unit_price' => $unitPrice,
            'line_subtotal' => $lineSubtotal,
            'stock_quantity' => $variant->stock_quantity,
            'formatted' => array_merge($data['formatted'], [
                'unit_price' => VndMoney::format($unitPrice),
                'line_subtotal' => VndMoney::format($lineSubtotal),
            ]),
        ]);
    }

    /**
     * @param  Collection<int, array{
     *     variant: ProductVariant,
     *     quantity: int,
     *     unit_price: int,
     *     line_subtotal: int,
     *     is_purchasable: bool,
     * }>  $lines
     * @return list<array<string, mixed>>
     */
    private static function presentItems(Collection $lines): array
    {
        return $lines->map(function (array $line): array {
            $variant = $line['variant'];

            return [
                'variant_id' => $variant->id,
                'quantity' => $line['quantity'],
                'unit_price' => $line['unit_price'],
                'line_subtotal' => $line['line_subtotal'],
                'stock_quantity' => $variant->stock_quantity,
                'is_available' => $line['is_purchasable'],
                'formatted' => [
                    'unit_price' => VndMoney::format($line['unit_price']),
                    'line_subtotal' => VndMoney::format($line['line_subtotal']),
                ],
            ];
        })->values()->all();
    }
}
