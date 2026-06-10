@extends('layouts.admin')

@section('title', 'Đơn hàng - Quản trị')
@section('heading', 'Đơn hàng')

@section('content')
    <form method="get" action="{{ route('admin.orders.index') }}" class="mb-4 rounded-lg border border-gray-200 bg-white p-4">
        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-5">
            <div class="lg:col-span-2">
                <label for="q" class="block text-xs font-medium text-gray-600">Mã đơn</label>
                <input
                    id="q"
                    type="search"
                    name="q"
                    value="{{ $filters['q'] ?? '' }}"
                    placeholder="ORD-..."
                    class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                >
            </div>
            <div>
                <label for="status" class="block text-xs font-medium text-gray-600">Trạng thái</label>
                <select id="status" name="status" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                    <option value="">Tất cả</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status->value }}" @selected(($filters['status'] ?? '') === $status->value)>
                            {{ $status->label() }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="date_from" class="block text-xs font-medium text-gray-600">Từ ngày</label>
                <input
                    id="date_from"
                    type="date"
                    name="date_from"
                    value="{{ $filters['date_from'] ?? '' }}"
                    class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                >
            </div>
            <div>
                <label for="date_to" class="block text-xs font-medium text-gray-600">Đến ngày</label>
                <input
                    id="date_to"
                    type="date"
                    name="date_to"
                    value="{{ $filters['date_to'] ?? '' }}"
                    class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                >
            </div>
        </div>
        <div class="mt-3 flex flex-wrap gap-2">
            <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                Lọc
            </button>
            <a href="{{ route('admin.orders.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Xóa bộ lọc
            </a>
        </div>
    </form>

    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase text-gray-500">
                <tr>
                    <th class="px-4 py-3">Mã đơn</th>
                    <th class="px-4 py-3">Khách hàng</th>
                    <th class="px-4 py-3">Ngày đặt</th>
                    <th class="px-4 py-3">Trạng thái</th>
                    <th class="px-4 py-3">Tổng tiền</th>
                    <th class="px-4 py-3 text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($orders as $order)
                    <tr>
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $order->order_code }}</td>
                        <td class="px-4 py-3 text-gray-600">
                            <div>{{ $order->receiver_name }}</div>
                            <div class="text-xs text-gray-500">{{ $order->user?->email }}</div>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3">
                            <x-order-status-badge :status="$order->status" />
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-900">
                            <x-money :amount="$order->total_amount" />
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a
                                href="{{ route('admin.orders.show', $order) }}"
                                class="rounded-md border border-gray-300 px-3 py-1.5 text-gray-700 hover:bg-gray-50"
                            >
                                Chi tiết
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">Không tìm thấy đơn hàng.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $orders->links() }}</div>
@endsection
