<?php

namespace App\Actions\Orders;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\ProductVariant;
use App\Models\User;
use App\Services\CartService;
use App\Services\ShippingFeeCalculator;
use App\Support\OrderCodeGenerator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PlaceOrder
{
    public function __construct(private CartService $cart) {}

    /**
     * @param  array{
     *     receiver_name: string,
     *     receiver_phone: string,
     *     province: string,
     *     district: string,
     *     ward: string,
     *     address_line: string,
     *     note?: string|null,
     * }  $shippingData
     */
    public function execute(User $user, array $shippingData): Order
    {
        $cartLines = $this->cart->getItems();

        if ($cartLines->isEmpty()) {
            throw ValidationException::withMessages([
                'cart' => ['Giỏ hàng trống.'],
            ]);
        }

        if ($cartLines->contains(fn (array $line): bool => ! $line['is_purchasable'])) {
            throw ValidationException::withMessages([
                'cart' => ['Một số sản phẩm trong giỏ không còn khả dụng. Vui lòng cập nhật giỏ hàng trước khi thanh toán.'],
            ]);
        }

        return DB::transaction(function () use ($user, $shippingData, $cartLines): Order {
            /** @var Collection<int, ProductVariant> $lockedVariants */
            $lockedVariants = ProductVariant::query()
                ->with(['product', 'color', 'storageOption'])
                ->whereIn('id', $cartLines->map(fn (array $line): int => $line['variant']->id)->all())
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $subtotal = 0;
            $preparedItems = [];

            foreach ($cartLines as $line) {
                $variantId = $line['variant']->id;
                $quantity = (int) $line['quantity'];
                $variant = $lockedVariants->get($variantId);

                $this->assertVariantReadyForCheckout($variant, $quantity, $variantId);

                $unitPrice = (int) $variant->sale_price;
                $lineTotal = $unitPrice * $quantity;
                $subtotal += $lineTotal;

                $preparedItems[] = [
                    'variant' => $variant,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'line_total' => $lineTotal,
                ];
            }

            $shippingFee = ShippingFeeCalculator::calculate($subtotal);
            $totalAmount = $subtotal + $shippingFee;

            $order = Order::query()->create([
                'order_code' => OrderCodeGenerator::generate(),
                'user_id' => $user->id,
                'receiver_name' => $shippingData['receiver_name'],
                'receiver_phone' => $shippingData['receiver_phone'],
                'province' => $shippingData['province'],
                'district' => $shippingData['district'],
                'ward' => $shippingData['ward'],
                'address_line' => $shippingData['address_line'],
                'note' => $shippingData['note'] ?? null,
                'payment_method' => 'cod',
                'subtotal' => $subtotal,
                'shipping_fee' => $shippingFee,
                'total_amount' => $totalAmount,
                'status' => OrderStatus::Pending,
            ]);

            foreach ($preparedItems as $item) {
                /** @var ProductVariant $variant */
                $variant = $item['variant'];
                $product = $variant->product;

                $order->items()->create([
                    'product_id' => $product?->id,
                    'product_variant_id' => $variant->id,
                    'product_name' => $product?->name ?? 'Sản phẩm',
                    'sku' => $variant->sku,
                    'color_name' => $variant->color?->name ?? '',
                    'storage_label' => $variant->storageOption?->label ?? '',
                    'unit_price' => $item['unit_price'],
                    'quantity' => $item['quantity'],
                    'line_total' => $item['line_total'],
                ]);

                $variant->decrement('stock_quantity', $item['quantity']);
            }

            $this->cart->clear();

            return $order->load('items');
        });
    }

    private function assertVariantReadyForCheckout(?ProductVariant $variant, int $quantity, int $variantId): void
    {
        if ($variant === null) {
            throw ValidationException::withMessages([
                'cart' => ["Biến thể #{$variantId} không còn tồn tại."],
            ]);
        }

        if (! $variant->is_active) {
            throw ValidationException::withMessages([
                'cart' => ["Biến thể {$variant->sku} không còn khả dụng."],
            ]);
        }

        $product = $variant->product;

        if ($product === null || ! $product->is_active || $product->trashed()) {
            throw ValidationException::withMessages([
                'cart' => ["Sản phẩm cho biến thể {$variant->sku} không còn khả dụng."],
            ]);
        }

        if ($variant->stock_quantity <= 0) {
            throw ValidationException::withMessages([
                'cart' => ["{$product->name} đã hết hàng."],
            ]);
        }

        if ($quantity > $variant->stock_quantity) {
            throw ValidationException::withMessages([
                'cart' => ["{$product->name} chỉ còn {$variant->stock_quantity} sản phẩm trong kho."],
            ]);
        }
    }
}
