<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 text-gray-900 antialiased">
    <header class="border-b border-gray-200 bg-white">
        <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
            <div class="flex items-center justify-between gap-4">
                <a href="{{ route('home') }}" class="text-lg font-semibold text-gray-900">
                    {{ config('app.name') }}
                </a>
                <button
                    type="button"
                    class="inline-flex items-center rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-700 sm:hidden"
                    data-action="toggle-mobile-nav"
                    aria-expanded="false"
                    aria-controls="mobile-nav"
                >
                    Menu
                </button>
            </div>

            <form action="#" method="get" class="w-full max-w-xl sm:mx-4">
                <label for="search" class="sr-only">Tìm kiếm sản phẩm</label>
                <div class="flex gap-2">
                    <input
                        id="search"
                        type="search"
                        name="q"
                        placeholder="Tìm iPhone theo tên hoặc SKU"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                        disabled
                    >
                    <button
                        type="submit"
                        class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
                        disabled
                    >
                        Tìm
                    </button>
                </div>
            </form>

            <nav id="mobile-nav" class="hidden flex-col gap-2 text-sm sm:flex sm:flex-row sm:items-center sm:gap-4" data-mobile-nav>
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600">Trang chủ</a>
                <span class="text-gray-400">Sản phẩm</span>
                <span class="text-gray-400">Giỏ hàng (0)</span>
                <span class="text-gray-400">Đăng nhập</span>
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <x-flash-message />
        @yield('content')
    </main>

    <footer class="mt-auto border-t border-gray-200 bg-white">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="grid gap-6 md:grid-cols-3">
                <div>
                    <h2 class="text-sm font-semibold text-gray-900">{{ config('app.name') }}</h2>
                    <p class="mt-2 text-sm text-gray-600">
                        Cửa hàng điện thoại chuyên Apple phục vụ mục đích học tập.
                    </p>
                </div>
                <div>
                    <h2 class="text-sm font-semibold text-gray-900">Chính sách</h2>
                    <ul class="mt-2 space-y-1 text-sm text-gray-600">
                        <li>Giao hàng toàn quốc</li>
                        <li>Bảo hành chính hãng</li>
                        <li>Đổi trả theo quy định</li>
                    </ul>
                </div>
                <div>
                    <h2 class="text-sm font-semibold text-gray-900">Liên hệ</h2>
                    <p class="mt-2 text-sm text-gray-600">
                        Hotline: 1900 0000<br>
                        Email: support@example.com
                    </p>
                </div>
            </div>
            <p class="mt-8 text-center text-xs text-gray-500">
                &copy; {{ date('Y') }} {{ config('app.name') }}. Dự án học tập, không phải cửa hàng Apple chính thức.
            </p>
        </div>
    </footer>
</body>
</html>
