@extends('layouts.admin')

@section('title', 'Dashboard - Quản trị')
@section('heading', 'Dashboard')

@section('content')
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-6">
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="text-sm text-gray-500">Sản phẩm đang hoạt động</p>
            <p class="mt-2 text-2xl font-semibold text-gray-900">{{ number_format($active_products) }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="text-sm text-gray-500">Khách hàng</p>
            <p class="mt-2 text-2xl font-semibold text-gray-900">{{ number_format($customers) }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="text-sm text-gray-500">Tổng đơn hàng</p>
            <p class="mt-2 text-2xl font-semibold text-gray-900">{{ number_format($total_orders) }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="text-sm text-gray-500">Đơn chờ xác nhận</p>
            <p class="mt-2 text-2xl font-semibold text-amber-700">{{ number_format($pending_orders) }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5 sm:col-span-2 2xl:col-span-2">
            <p class="text-sm text-gray-500">Doanh thu đơn hoàn thành</p>
            <p class="mt-2 text-2xl font-semibold text-green-700">
                <x-money :amount="$completed_revenue" />
            </p>
        </div>
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-2">
        <section class="rounded-xl border border-gray-200 bg-white p-6">
            <div class="flex items-center justify-between gap-3">
                <h2 class="text-lg font-semibold text-gray-900">Đơn hàng mới nhất</h2>
                <a href="{{ route('admin.orders.index') }}" class="text-sm font-medium text-blue-600 hover:underline">
                    Xem tất cả
                </a>
            </div>

            @if ($latest_orders->isEmpty())
                <p class="mt-4 text-sm text-gray-500">Chưa có đơn hàng.</p>
            @else
                <div class="mt-4 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-left text-xs font-semibold uppercase text-gray-500">
                            <tr>
                                <th class="px-3 py-2">Mã đơn</th>
                                <th class="px-3 py-2">Khách</th>
                                <th class="px-3 py-2">Trạng thái</th>
                                <th class="px-3 py-2 text-right">Tổng</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($latest_orders as $order)
                                <tr>
                                    <td class="px-3 py-2">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="font-medium text-blue-600 hover:underline">
                                            {{ $order->order_code }}
                                        </a>
                                        <div class="text-xs text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                                    </td>
                                    <td class="px-3 py-2 text-gray-600">{{ $order->user?->name ?? $order->receiver_name }}</td>
                                    <td class="px-3 py-2">
                                        <x-order-status-badge :status="$order->status" />
                                    </td>
                                    <td class="px-3 py-2 text-right font-medium">
                                        <x-money :amount="$order->total_amount" />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

        <section class="rounded-xl border border-gray-200 bg-white p-6">
            <div class="flex items-center justify-between gap-3">
                <h2 class="text-lg font-semibold text-gray-900">Biến thể sắp hết hàng</h2>
                <span class="text-xs text-gray-500">≤ {{ $low_stock_threshold }} sản phẩm</span>
            </div>

            @if ($low_stock_variants->isEmpty())
                <p class="mt-4 text-sm text-gray-500">Không có biến thể nào dưới ngưỡng cảnh báo.</p>
            @else
                <ul class="mt-4 divide-y divide-gray-200">
                    @foreach ($low_stock_variants as $variant)
                        <li class="flex items-start justify-between gap-3 py-3 text-sm">
                            <div class="min-w-0">
                                <p class="font-medium text-gray-900">{{ $variant->product?->name ?? 'Sản phẩm' }}</p>
                                <p class="mt-0.5 text-xs text-gray-500">{{ $variant->sku }}</p>
                            </div>
                            <div class="shrink-0 text-right">
                                <p class="font-semibold {{ $variant->stock_quantity === 0 ? 'text-red-600' : 'text-amber-700' }}">
                                    {{ $variant->stock_quantity }} còn
                                </p>
                                @if ($variant->product)
                                    <a
                                        href="{{ route('admin.products.variants.index', $variant->product) }}"
                                        class="text-xs text-blue-600 hover:underline"
                                    >
                                        Quản lý
                                    </a>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>
    </div>
@endsection
