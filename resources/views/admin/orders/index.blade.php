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
        <div class="admin-form-actions mt-3">
            <button type="submit" class="admin-btn-primary">
                Lọc
            </button>
            <a href="{{ route('admin.orders.index') }}" class="admin-btn-secondary px-4 py-2">
                Xóa bộ lọc
            </a>
        </div>
    </form>

    <div class="admin-table-panel">
        <div class="admin-table-scroll">
        <table class="admin-table">
            <thead>
                <tr>
                    <th class="admin-th">Mã đơn</th>
                    <th class="admin-th">Khách hàng</th>
                    <th class="admin-th">Ngày đặt</th>
                    <th class="admin-th">Trạng thái</th>
                    <th class="admin-th">Tổng tiền</th>
                    <th class="admin-th text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($orders as $order)
                    <tr>
                        <td class="admin-td font-medium text-gray-900">{{ $order->order_code }}</td>
                        <td class="admin-td text-gray-600">
                            <div>{{ $order->receiver_name }}</div>
                            <div class="text-xs text-gray-500">{{ $order->user?->email }}</div>
                        </td>
                        <td class="admin-td text-gray-600">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td class="admin-td">
                            <x-order-status-badge :status="$order->status" />
                        </td>
                        <td class="admin-td font-medium text-gray-900">
                            <x-money :amount="$order->total_amount" />
                        </td>
                        <td class="admin-td">
                            <div class="admin-actions">
                                <a href="{{ route('admin.orders.show', $order) }}" class="admin-btn-secondary">
                                    Chi tiết
                                </a>
                            </div>
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
    </div>

    <div class="mt-4">{{ $orders->links() }}</div>
@endsection
