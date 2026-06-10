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
        <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
            Tìm kiếm
        </button>
    </form>

    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase text-gray-500">
                <tr>
                    <th class="px-4 py-3">Họ tên</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Điện thoại</th>
                    <th class="px-4 py-3">Trạng thái</th>
                    <th class="px-4 py-3">Đơn hàng</th>
                    <th class="px-4 py-3 text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($customers as $customer)
                    <tr>
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $customer->name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $customer->email }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $customer->phone }}</td>
                        <td class="px-4 py-3">
                            <x-user-status-badge :status="$customer->status" />
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $customer->orders_count }}</td>
                        <td class="px-4 py-3 text-right">
                            <a
                                href="{{ route('admin.customers.show', $customer) }}"
                                class="rounded-md border border-gray-300 px-3 py-1.5 text-gray-700 hover:bg-gray-50"
                            >
                                Chi tiết
                            </a>
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

    <div class="mt-4">{{ $customers->links() }}</div>
@endsection
