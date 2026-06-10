@extends('layouts.admin')

@section('title', 'Đơn ' . $order->order_code . ' - Quản trị')
@section('heading', 'Chi tiết đơn hàng')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.orders.index') }}" class="text-sm text-gray-600 hover:text-blue-600">
            ← Quay lại danh sách
        </a>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <section class="rounded-lg border border-gray-200 bg-white p-5">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">{{ $order->order_code }}</h2>
                        <p class="mt-1 text-sm text-gray-500">Đặt lúc {{ $order->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <x-order-status-badge :status="$order->status" />
                </div>
            </section>

            <section class="rounded-lg border border-gray-200 bg-white p-5">
                <h3 class="text-base font-semibold text-gray-900">Khách hàng & giao hàng</h3>
                <dl class="mt-4 grid gap-3 text-sm sm:grid-cols-2">
                    <div>
                        <dt class="text-gray-500">Tài khoản</dt>
                        <dd class="mt-1 font-medium text-gray-900">
                            @if ($order->user)
                                <a href="{{ route('admin.customers.show', $order->user) }}" class="text-blue-600 hover:underline">
                                    {{ $order->user->name }}
                                </a>
                            @else
                                —
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Email</dt>
                        <dd class="mt-1 text-gray-900">{{ $order->user?->email ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Người nhận</dt>
                        <dd class="mt-1 text-gray-900">{{ $order->receiver_name }} · {{ $order->receiver_phone }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-gray-500">Địa chỉ</dt>
                        <dd class="mt-1 text-gray-900">
                            {{ $order->address_line }}, {{ $order->ward }}, {{ $order->district }}, {{ $order->province }}
                        </dd>
                    </div>
                    @if ($order->note)
                        <div class="sm:col-span-2">
                            <dt class="text-gray-500">Ghi chú</dt>
                            <dd class="mt-1 text-gray-900">{{ $order->note }}</dd>
                        </div>
                    @endif
                </dl>
            </section>

            <section class="rounded-lg border border-gray-200 bg-white p-5">
                <h3 class="text-base font-semibold text-gray-900">Sản phẩm (snapshot)</h3>
                <div class="mt-4 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-left text-xs font-semibold uppercase text-gray-500">
                            <tr>
                                <th class="px-3 py-2">Sản phẩm</th>
                                <th class="px-3 py-2">SKU</th>
                                <th class="px-3 py-2">Đơn giá</th>
                                <th class="px-3 py-2">SL</th>
                                <th class="px-3 py-2 text-right">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($order->items as $item)
                                <tr>
                                    <td class="px-3 py-2">
                                        <div class="font-medium text-gray-900">{{ $item->product_name }}</div>
                                        <div class="text-xs text-gray-500">
                                            {{ trim($item->color_name.' '.$item->storage_label) }}
                                        </div>
                                    </td>
                                    <td class="px-3 py-2 text-gray-600">{{ $item->sku }}</td>
                                    <td class="px-3 py-2"><x-money :amount="$item->unit_price" /></td>
                                    <td class="px-3 py-2">{{ $item->quantity }}</td>
                                    <td class="px-3 py-2 text-right font-medium"><x-money :amount="$item->line_total" /></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        <div class="space-y-6">
            <section class="rounded-lg border border-gray-200 bg-white p-5">
                <h3 class="text-base font-semibold text-gray-900">Tổng tiền</h3>
                <dl class="mt-4 space-y-2 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-600">Tạm tính</dt>
                        <dd><x-money :amount="$order->subtotal" /></dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-600">Phí vận chuyển</dt>
                        <dd><x-money :amount="$order->shipping_fee" /></dd>
                    </div>
                    <div class="flex justify-between border-t border-gray-200 pt-2 font-semibold">
                        <dt>Tổng thanh toán</dt>
                        <dd><x-money :amount="$order->total_amount" /></dd>
                    </div>
                </dl>
            </section>

            @if ($nextStatuses !== [])
                <section class="rounded-lg border border-gray-200 bg-white p-5">
                    <h3 class="text-base font-semibold text-gray-900">Cập nhật trạng thái</h3>
                    <form method="post" action="{{ route('admin.orders.status.update', $order) }}" class="mt-4 space-y-3">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                            @foreach ($nextStatuses as $status)
                                <option value="{{ $status->value }}">{{ $status->label() }}</option>
                            @endforeach
                        </select>
                        @error('status')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <button type="submit" class="w-full rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                            Lưu trạng thái
                        </button>
                    </form>
                </section>
            @endif

            @if ($order->status->canBeCancelled())
                <section class="rounded-lg border border-red-200 bg-red-50 p-5">
                    <h3 class="text-base font-semibold text-red-900">Hủy đơn hàng</h3>
                    <p class="mt-2 text-sm text-red-800">
                        Hủy đơn sẽ hoàn tồn kho cho các biến thể còn tồn tại. Thao tác không thể hoàn tác.
                    </p>
                    <form
                        method="post"
                        action="{{ route('admin.orders.cancel', $order) }}"
                        class="mt-4"
                        onsubmit="return confirm('Xác nhận hủy đơn hàng này?');"
                    >
                        @csrf
                        @error('order')
                            <p class="mb-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <button type="submit" class="w-full rounded-lg border border-red-300 bg-white px-4 py-2 text-sm font-medium text-red-700 hover:bg-red-100">
                            Hủy đơn & hoàn kho
                        </button>
                    </form>
                </section>
            @endif

            @if ($order->completed_at)
                <p class="text-sm text-gray-600">Hoàn thành: {{ $order->completed_at->format('d/m/Y H:i') }}</p>
            @endif
            @if ($order->cancelled_at)
                <p class="text-sm text-gray-600">Đã hủy: {{ $order->cancelled_at->format('d/m/Y H:i') }}</p>
            @endif
        </div>
    </div>
@endsection
