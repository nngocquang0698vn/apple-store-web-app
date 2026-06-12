@extends('layouts.admin')

@section('title', 'Khách hàng - Quản trị')
@section('heading', 'Khách hàng')

@section('content')
    <form method="get" action="{{ route('admin.customers.index') }}" class="mb-4 flex flex-col gap-3 sm:flex-row">
        <input
            type="search"
            name="q"
            value="{{ $filters['q'] ?? '' }}"
            placeholder="Tìm theo tên, email hoặc số điện thoại"
            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm sm:max-w-md"
        >
        <button type="submit" class="admin-btn-primary shrink-0">
            Tìm kiếm
        </button>
    </form>

    <div class="admin-table-panel">
        <div class="admin-table-scroll">
        <table class="admin-table">
            <thead>
                <tr>
                    <th class="admin-th">Họ tên</th>
                    <th class="admin-th">Email</th>
                    <th class="admin-th">Điện thoại</th>
                    <th class="admin-th">Trạng thái</th>
                    <th class="admin-th">Đơn hàng</th>
                    <th class="admin-th text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($customers as $customer)
                    <tr>
                        <td class="admin-td font-medium text-gray-900">{{ $customer->name }}</td>
                        <td class="admin-td text-gray-600">{{ $customer->email }}</td>
                        <td class="admin-td text-gray-600">{{ $customer->phone }}</td>
                        <td class="admin-td">
                            <x-user-status-badge :status="$customer->status" />
                        </td>
                        <td class="admin-td text-gray-600">{{ $customer->orders_count }}</td>
                        <td class="admin-td">
                            <div class="admin-actions">
                                <a href="{{ route('admin.customers.show', $customer) }}" class="admin-btn-secondary">
                                    Chi tiết
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">Không tìm thấy khách hàng.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    <div class="mt-4">{{ $customers->links() }}</div>
@endsection
