@extends('layouts.app')

@section('title', 'Đặt hàng thành công - ' . config('app.name'))

@section('content')
    <div class="mx-auto max-w-3xl rounded-2xl border border-green-200 bg-white p-8 shadow-sm">
        <div class="text-center">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-green-100 text-green-700">
                <i class="fa-solid fa-circle-check text-2xl" aria-hidden="true"></i>
            </div>
            <h1 class="mt-4 text-2xl font-bold text-gray-900">Đặt hàng thành công</h1>
            <p class="mt-2 text-sm text-gray-600">
                Cảm ơn bạn đã đặt hàng. Chúng tôi sẽ liên hệ để xác nhận đơn hàng sớm nhất.
            </p>
        </div>

        <dl class="mt-8 space-y-3 rounded-xl border border-gray-200 bg-gray-50 p-5 text-sm">
            <div class="flex justify-between gap-4">
                <dt class="text-gray-600">Mã đơn hàng</dt>
                <dd class="font-semibold text-gray-900">{{ $order->order_code }}</dd>
            </div>
            <div class="flex justify-between gap-4">
                <dt class="text-gray-600">Trạng thái</dt>
                <dd class="font-medium text-gray-900">{{ $order->status->label() }}</dd>
            </div>
            <div class="flex justify-between gap-4">
                <dt class="text-gray-600">Người nhận</dt>
                <dd class="text-right text-gray-900">{{ $order->receiver_name }} · {{ $order->receiver_phone }}</dd>
            </div>
            <div class="flex justify-between gap-4">
                <dt class="text-gray-600">Địa chỉ giao hàng</dt>
                <dd class="text-right text-gray-900">
                    {{ $order->address_line }}, {{ $order->ward }}, {{ $order->district }}, {{ $order->province }}
                </dd>
            </div>
            <div class="flex justify-between gap-4 border-t border-gray-200 pt-3">
                <dt class="font-medium text-gray-900">Tổng thanh toán (COD)</dt>
                <dd class="text-lg font-bold text-gray-900">
                    <x-money :amount="$order->total_amount" />
                </dd>
            </div>
        </dl>

        <div class="mt-8">
            <h2 class="text-base font-semibold text-gray-900">Sản phẩm đã đặt</h2>
            <ul class="mt-4 space-y-3">
                @foreach ($order->items as $item)
                    <li class="flex items-start justify-between gap-4 rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm">
                        <div>
                            <p class="font-medium text-gray-900">{{ $item->product_name }}</p>
                            <p class="mt-1 text-xs text-gray-500">
                                {{ $item->sku }}
                                @if ($item->color_name || $item->storage_label)
                                    · {{ trim($item->color_name.' '.$item->storage_label) }}
                                @endif
                            </p>
                            <p class="mt-1 text-xs text-gray-500">Số lượng: {{ $item->quantity }}</p>
                        </div>
                        <p class="font-semibold text-gray-900">
                            <x-money :amount="$item->line_total" />
                        </p>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="mt-8 flex flex-wrap justify-center gap-3">
            <a
                href="{{ route('products.index') }}"
                class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
            >
                Tiếp tục mua sắm
            </a>
            <a
                href="{{ route('home') }}"
                class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
            >
                Về trang chủ
            </a>
        </div>
    </div>
@endsection
