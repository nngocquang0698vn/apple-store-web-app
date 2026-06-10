@extends('layouts.admin')

@section('title', 'Dashboard - Quản trị')
@section('heading', 'Dashboard')

@section('content')
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="text-sm text-gray-500">Sản phẩm đang hoạt động</p>
            <p class="mt-2 text-2xl font-semibold text-gray-900">—</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="text-sm text-gray-500">Khách hàng</p>
            <p class="mt-2 text-2xl font-semibold text-gray-900">—</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="text-sm text-gray-500">Đơn chờ xác nhận</p>
            <p class="mt-2 text-2xl font-semibold text-gray-900">—</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <p class="text-sm text-gray-500">Doanh thu hoàn thành</p>
            <p class="mt-2 text-2xl font-semibold text-gray-900">—</p>
        </div>
    </div>

    <section class="mt-8 rounded-xl border border-gray-200 bg-white p-6">
        <h2 class="text-lg font-semibold text-gray-900">Trạng thái dự án</h2>
        <p class="mt-2 text-sm text-gray-600">
            Đây là trang dashboard placeholder của Phase 0. Thống kê thật sẽ được triển khai ở Phase 10.
        </p>
        <ul class="mt-4 list-disc space-y-2 pl-5 text-sm text-gray-600">
            <li>Layout quản trị và sidebar cơ bản đã sẵn sàng.</li>
            <li>Middleware admin và quản lý catalog đã sẵn sàng.</li>
            <li>Đơn hàng, khách hàng và thống kê thật sẽ được bổ sung ở các phase sau.</li>
        </ul>
    </section>
@endsection
