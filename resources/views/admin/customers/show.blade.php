@extends('layouts.admin')

@section('title', $customer->name . ' - Khách hàng')
@section('heading', 'Chi tiết khách hàng')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.customers.index') }}" class="text-sm text-gray-600 hover:text-blue-600">
            ← Quay lại danh sách
        </a>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <section class="rounded-lg border border-gray-200 bg-white p-5 lg:col-span-2">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">{{ $customer->name }}</h2>
                    <p class="mt-1 text-sm text-gray-500">Tham gia {{ $customer->created_at->format('d/m/Y') }}</p>
                </div>
                <x-user-status-badge :status="$customer->status" />
            </div>

            <dl class="mt-6 grid gap-4 text-sm sm:grid-cols-2">
                <div>
                    <dt class="text-gray-500">Email</dt>
                    <dd class="mt-1 font-medium text-gray-900">{{ $customer->email }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Số điện thoại</dt>
                    <dd class="mt-1 font-medium text-gray-900">{{ $customer->phone }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-gray-500">Địa chỉ mặc định</dt>
                    <dd class="mt-1 text-gray-900">{{ $customer->default_address ?: '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Tổng đơn hàng</dt>
                    <dd class="mt-1 font-medium text-gray-900">{{ $customer->orders_count }}</dd>
                </div>
            </dl>
        </section>

        <section class="rounded-lg border border-gray-200 bg-white p-5">
            <h3 class="text-base font-semibold text-gray-900">Trạng thái tài khoản</h3>
            @if (auth()->id() === $customer->id)
                <p class="mt-2 text-sm text-amber-700">Không thể thay đổi trạng thái tài khoản của chính bạn.</p>
            @else
                <form method="post" action="{{ route('admin.customers.status.update', $customer) }}" class="mt-4 space-y-3">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                        @foreach (\App\Enums\UserStatus::cases() as $status)
                            <option value="{{ $status->value }}" @selected($customer->status === $status)>
                                {{ $status->label() }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="w-full rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                        Cập nhật
                    </button>
                </form>
            @endif
        </section>
    </div>

    <section class="mt-6 rounded-lg border border-gray-200 bg-white p-5">
        <h3 class="text-base font-semibold text-gray-900">Đơn hàng gần đây</h3>
        @if ($recentOrders->isEmpty())
            <p class="mt-4 text-sm text-gray-500">Chưa có đơn hàng.</p>
        @else
            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-left text-xs font-semibold uppercase text-gray-500">
                        <tr>
                            <th class="px-3 py-2">Mã đơn</th>
                            <th class="px-3 py-2">Ngày</th>
                            <th class="px-3 py-2">Trạng thái</th>
                            <th class="px-3 py-2 text-right">Tổng</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($recentOrders as $order)
                            <tr>
                                <td class="px-3 py-2">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="font-medium text-blue-600 hover:underline">
                                        {{ $order->order_code }}
                                    </a>
                                </td>
                                <td class="px-3 py-2 text-gray-600">{{ $order->created_at->format('d/m/Y') }}</td>
                                <td class="px-3 py-2"><x-order-status-badge :status="$order->status" /></td>
                                <td class="px-3 py-2 text-right"><x-money :amount="$order->total_amount" /></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
@endsection
