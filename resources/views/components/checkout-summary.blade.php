@props(['summary'])

<div {{ $attributes->merge(['class' => 'rounded-2xl border border-gray-200 bg-white p-6 shadow-sm']) }} data-checkout-summary>
    <h2 class="text-lg font-semibold text-gray-900">Đơn hàng của bạn</h2>

    <div class="mt-4 space-y-4" data-checkout-items>
        @foreach ($summary['items'] as $item)
            <div
                class="border-b border-gray-100 pb-4 last:border-b-0 last:pb-0"
                data-checkout-line="{{ $item['variant_id'] }}"
            >
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $item['product_name'] }}</p>
                        <p class="mt-1 text-xs text-gray-500">
                            {{ $item['sku'] }}
                            @if ($item['color_name'] || $item['storage_label'])
                                ·
                                {{ trim($item['color_name'].' '.$item['storage_label']) }}
                            @endif
                        </p>
                        <p class="mt-1 text-xs text-gray-500">Số lượng: {{ $item['quantity'] }}</p>
                    </div>
                    <p class="text-sm font-semibold text-gray-900" data-checkout-line-subtotal="{{ $item['variant_id'] }}">
                        <x-money :amount="$item['line_subtotal']" />
                    </p>
                </div>
                @if (! $item['is_purchasable'])
                    <p class="mt-2 text-xs text-amber-700" data-checkout-line-warning="{{ $item['variant_id'] }}">
                        Sản phẩm này cần được cập nhật trước khi thanh toán.
                    </p>
                @endif
            </div>
        @endforeach
    </div>

    <div class="mt-6 space-y-3 border-t border-gray-200 pt-4 text-sm" data-checkout-warnings>
        @foreach ($summary['warnings'] as $warning)
            <p class="rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-amber-800">{{ $warning }}</p>
        @endforeach
    </div>

    <dl class="mt-6 space-y-3 text-sm">
        <div class="flex items-center justify-between gap-4">
            <dt class="text-gray-600">Tạm tính</dt>
            <dd class="font-semibold text-gray-900" data-checkout-subtotal>
                <x-money :amount="$summary['cart_subtotal']" />
            </dd>
        </div>
        <div class="flex items-center justify-between gap-4">
            <dt class="text-gray-600">Phí vận chuyển</dt>
            <dd class="font-semibold text-gray-900" data-checkout-shipping>
                <x-money :amount="$summary['shipping_fee']" />
            </dd>
        </div>
        <div class="flex items-center justify-between gap-4 border-t border-gray-200 pt-3 text-base">
            <dt class="font-medium text-gray-900">Tổng thanh toán (COD)</dt>
            <dd class="text-xl font-bold text-gray-900" data-checkout-grand-total>
                <x-money :amount="$summary['grand_total']" />
            </dd>
        </div>
    </dl>

    <p class="mt-4 text-xs text-gray-500">
        Tổng tiền được tính lại từ hệ thống. Thanh toán khi nhận hàng.
    </p>
</div>
