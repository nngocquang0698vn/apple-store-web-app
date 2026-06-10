@extends('layouts.app')

@section('title', 'Trang chủ - ' . config('app.name'))

@section('content')
    <section class="rounded-2xl bg-white p-6 shadow-sm sm:p-10">
        <div class="grid gap-8 lg:grid-cols-2 lg:items-center">
            <div>
                <p class="text-sm font-medium text-blue-600">Chào mừng đến với iStore</p>
                <h1 class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                    Cửa hàng điện thoại Apple cho đồ án web
                </h1>
                <p class="mt-4 text-base text-gray-600">
                    Khám phá iPhone, iPad, iPod và phụ kiện sạc chính hãng. Tìm kiếm, lọc theo màu sắc
                    và dung lượng, thêm vào giỏ và đặt hàng COD ngay trên website.
                </p>
                <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                    <a
                        href="{{ route('products.index') }}"
                        class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-5 py-3 text-sm font-medium text-white hover:bg-blue-700"
                    >
                        Xem sản phẩm
                    </a>
                    <a
                        href="{{ route('admin.dashboard') }}"
                        class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-5 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50"
                    >
                        Vào khu vực quản trị
                    </a>
                </div>
            </div>

            <x-home-product-showcase :products="$showcaseProducts" />
        </div>
    </section>

    <section class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <article class="rounded-xl border border-gray-200 bg-white p-5">
            <h2 class="text-base font-semibold text-gray-900">Giao hàng nhanh</h2>
            <p class="mt-2 text-sm text-gray-600">Hỗ trợ giao hàng COD trên toàn quốc.</p>
        </article>
        <article class="rounded-xl border border-gray-200 bg-white p-5">
            <h2 class="text-base font-semibold text-gray-900">Bảo hành rõ ràng</h2>
            <p class="mt-2 text-sm text-gray-600">Thông tin bảo hành minh bạch cho từng sản phẩm.</p>
        </article>
        <article class="rounded-xl border border-gray-200 bg-white p-5 sm:col-span-2 lg:col-span-1">
            <h2 class="text-base font-semibold text-gray-900">Sản phẩm chính hãng</h2>
            <p class="mt-2 text-sm text-gray-600">Tập trung vào các dòng iPhone, iPad và phụ kiện Apple.</p>
        </article>
    </section>
@endsection
