@extends('layouts.app')

@section('title', 'Đơn hàng của tôi - ' . config('app.name'))

@section('content')
    <section class="mx-auto max-w-4xl space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Đơn hàng của tôi</h1>
            <p class="mt-2 text-sm text-gray-600">
                Theo dõi trạng thái và chi tiết các đơn hàng bạn đã đặt.
            </p>
        </div>

        <x-account-nav />

        @if ($orders->isEmpty())
            <div class="rounded-2xl border border-dashed border-gray-300 bg-white px-6 py-16 text-center">
                <i class="fa-solid fa-receipt text-3xl text-gray-400" aria-hidden="true"></i>
                <h2 class="mt-4 text-lg font-semibold text-gray-900">Chưa có đơn hàng</h2>
                <p class="mt-2 text-sm text-gray-600">
                    Bạn chưa đặt đơn hàng nào. Hãy khám phá sản phẩm và thêm vào giỏ hàng.
                </p>
                <a
                    href="{{ route('products.index') }}"
                    class="mt-6 inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
                >
                    Xem sản phẩm
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($orders as $order)
                    <article class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                            <div class="min-w-0 space-y-2">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h2 class="text-base font-semibold text-gray-900">
                                        <a
                                            href="{{ route('account.orders.show', $order) }}"
                                            class="hover:text-blue-600"
                                        >
                                            {{ $order->order_code }}
                                        </a>
                                    </h2>
                                    <x-order-status-badge :status="$order->status" />
                                </div>
                                <p class="text-sm text-gray-600">
                                    Đặt ngày {{ $order->created_at->format('d/m/Y H:i') }}
                                    · {{ $order->items_count }} sản phẩm
                                </p>
                            </div>
                            <div class="text-left sm:text-right">
                                <p class="text-xs text-gray-500">Tổng thanh toán</p>
                                <p class="text-lg font-bold text-gray-900">
                                    <x-money :amount="$order->total_amount" />
                                </p>
                            </div>
                        </div>

                        <div class="mt-4 flex flex-wrap gap-3">
                            <a
                                href="{{ route('account.orders.show', $order) }}"
                                class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                            >
                                Xem chi tiết
                                <i class="fa-solid fa-chevron-right text-xs" aria-hidden="true"></i>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            @if ($orders->hasPages())
                <div class="mt-8">
                    {{ $orders->links() }}
                </div>
            @endif
        @endif
    </section>
@endsection
