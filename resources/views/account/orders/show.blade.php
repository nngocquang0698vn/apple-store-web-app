@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng ' . $order->order_code . ' - ' . config('app.name'))

@section('content')
    <section class="mx-auto max-w-3xl space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <a
                    href="{{ route('account.orders.index') }}"
                    class="inline-flex items-center gap-1.5 text-sm text-gray-600 hover:text-blue-600"
                >
                    <i class="fa-solid fa-arrow-left text-xs" aria-hidden="true"></i>
                    Quay lại danh sách
                </a>
                <h1 class="mt-3 text-2xl font-bold text-gray-900">Chi tiết đơn hàng</h1>
                <p class="mt-1 text-sm text-gray-600">Mã đơn: {{ $order->order_code }}</p>
            </div>
            <x-order-status-badge :status="$order->status" class="self-start" />
        </div>

        <x-account-nav />

        <dl class="grid gap-4 rounded-2xl border border-gray-200 bg-white p-5 text-sm shadow-sm sm:grid-cols-2 sm:p-6">
            <div>
                <dt class="text-gray-500">Ngày đặt</dt>
                <dd class="mt-1 font-medium text-gray-900">{{ $order->created_at->format('d/m/Y H:i') }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Thanh toán</dt>
                <dd class="mt-1 font-medium text-gray-900">Thanh toán khi nhận hàng (COD)</dd>
            </div>
            @if ($order->completed_at)
                <div>
                    <dt class="text-gray-500">Hoàn thành</dt>
                    <dd class="mt-1 font-medium text-gray-900">{{ $order->completed_at->format('d/m/Y H:i') }}</dd>
                </div>
            @endif
            @if ($order->cancelled_at)
                <div>
                    <dt class="text-gray-500">Đã hủy</dt>
                    <dd class="mt-1 font-medium text-gray-900">{{ $order->cancelled_at->format('d/m/Y H:i') }}</dd>
                </div>
            @endif
        </dl>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6">
            <h2 class="text-base font-semibold text-gray-900">Thông tin giao hàng</h2>
            <dl class="mt-4 space-y-3 text-sm">
                <div>
                    <dt class="text-gray-500">Người nhận</dt>
                    <dd class="mt-1 font-medium text-gray-900">{{ $order->receiver_name }} · {{ $order->receiver_phone }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Địa chỉ</dt>
                    <dd class="mt-1 text-gray-900">
                        {{ $order->address_line }}, {{ $order->ward }}, {{ $order->district }}, {{ $order->province }}
                    </dd>
                </div>
                @if ($order->note)
                    <div>
                        <dt class="text-gray-500">Ghi chú</dt>
                        <dd class="mt-1 text-gray-900">{{ $order->note }}</dd>
                    </div>
                @endif
            </dl>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6">
            <h2 class="text-base font-semibold text-gray-900">Sản phẩm đã đặt</h2>
            <ul class="mt-4 divide-y divide-gray-100">
                @foreach ($order->items as $item)
                    <li class="flex flex-col gap-3 py-4 first:pt-0 last:pb-0 sm:flex-row sm:items-start sm:justify-between">
                        <div class="min-w-0">
                            <p class="font-medium text-gray-900">{{ $item->product_name }}</p>
                            <p class="mt-1 text-xs text-gray-500">
                                SKU: {{ $item->sku }}
                                @if ($item->color_name || $item->storage_label)
                                    · {{ trim($item->color_name.' '.$item->storage_label) }}
                                @endif
                            </p>
                            <p class="mt-1 text-xs text-gray-500">
                                Đơn giá: <x-money :amount="$item->unit_price" /> · Số lượng: {{ $item->quantity }}
                            </p>
                        </div>
                        <p class="shrink-0 font-semibold text-gray-900">
                            <x-money :amount="$item->line_total" />
                        </p>
                    </li>
                @endforeach
            </ul>

            <dl class="mt-6 space-y-2 border-t border-gray-200 pt-4 text-sm">
                <div class="flex justify-between gap-4">
                    <dt class="text-gray-600">Tạm tính</dt>
                    <dd class="font-medium text-gray-900">
                        <x-money :amount="$order->subtotal" />
                    </dd>
                </div>
                <div class="flex justify-between gap-4">
                    <dt class="text-gray-600">Phí vận chuyển</dt>
                    <dd class="font-medium text-gray-900">
                        <x-money :amount="$order->shipping_fee" />
                    </dd>
                </div>
                <div class="flex justify-between gap-4 border-t border-gray-200 pt-2">
                    <dt class="font-semibold text-gray-900">Tổng thanh toán</dt>
                    <dd class="text-lg font-bold text-gray-900">
                        <x-money :amount="$order->total_amount" />
                    </dd>
                </div>
            </dl>
        </div>
    </section>
@endsection
