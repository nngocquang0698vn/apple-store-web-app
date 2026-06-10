<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Quản trị - ' . config('app.name'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100 text-gray-900 antialiased">
    <div class="flex min-h-screen flex-col lg:flex-row">
        <aside
            class="border-b border-gray-200 bg-white lg:w-64 lg:border-b-0 lg:border-r"
            data-admin-sidebar
        >
            <div class="flex items-center justify-between px-4 py-4 lg:block">
                <a href="{{ route('admin.dashboard') }}" class="text-lg font-semibold text-gray-900">
                    Quản trị
                </a>
                <button
                    type="button"
                    class="rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-700 lg:hidden"
                    data-action="toggle-admin-sidebar"
                    aria-expanded="false"
                    aria-controls="admin-sidebar-nav"
                    aria-label="Mở menu quản trị"
                >
                    <i class="fa-solid fa-bars" aria-hidden="true"></i>
                </button>
            </div>

            <nav id="admin-sidebar-nav" class="hidden px-4 pb-4 lg:block" data-admin-sidebar-nav>
                <ul class="space-y-1 text-sm">
                    <li>
                        <a
                            href="{{ route('admin.dashboard') }}"
                            class="block rounded-lg px-3 py-2 font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}"
                        >
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a
                            href="{{ route('admin.categories.index') }}"
                            class="block rounded-lg px-3 py-2 font-medium {{ request()->routeIs('admin.categories.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}"
                        >
                            Danh mục
                        </a>
                    </li>
                    <li>
                        <a
                            href="{{ route('admin.product-series.index') }}"
                            class="block rounded-lg px-3 py-2 font-medium {{ request()->routeIs('admin.product-series.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}"
                        >
                            Dòng sản phẩm
                        </a>
                    </li>
                    <li>
                        <a
                            href="{{ route('admin.colors.index') }}"
                            class="block rounded-lg px-3 py-2 font-medium {{ request()->routeIs('admin.colors.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}"
                        >
                            Màu sắc
                        </a>
                    </li>
                    <li>
                        <a
                            href="{{ route('admin.storage-options.index') }}"
                            class="block rounded-lg px-3 py-2 font-medium {{ request()->routeIs('admin.storage-options.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}"
                        >
                            Dung lượng
                        </a>
                    </li>
                    <li>
                        <a
                            href="{{ route('admin.products.index') }}"
                            class="block rounded-lg px-3 py-2 font-medium {{ request()->routeIs('admin.products.*') || request()->routeIs('admin.product-images.*') || request()->routeIs('admin.variants.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}"
                        >
                            Sản phẩm
                        </a>
                    </li>
                    <li class="pt-4 border-t border-gray-200">
                        <a href="{{ route('home') }}" class="block rounded-lg px-3 py-2 text-gray-600 hover:bg-gray-50">
                            Về trang khách hàng
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <div class="flex flex-1 flex-col">
            <header class="border-b border-gray-200 bg-white px-4 py-4 sm:px-6">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-500">Khu vực quản trị</p>
                        <h1 class="text-lg font-semibold text-gray-900">@yield('heading', 'Dashboard')</h1>
                    </div>
                    <p class="text-sm text-gray-500">{{ config('app.name') }}</p>
                </div>
            </header>

            <main class="flex-1 px-4 py-6 sm:px-6">
                <x-flash-message />
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
