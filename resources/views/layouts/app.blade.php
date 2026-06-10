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
        <div class="mx-auto flex max-w-7xl flex-col gap-3 px-4 py-3 sm:flex-row sm:items-center sm:gap-3 lg:gap-4 sm:px-6 lg:px-8">
            <div class="flex min-w-0 items-center justify-between gap-3 sm:shrink-0 sm:justify-start">
                <a href="{{ route('home') }}" class="shrink-0 text-lg font-semibold text-gray-900">
                    {{ config('app.name') }}
                </a>
                <button
                    type="button"
                    class="inline-flex shrink-0 items-center rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-700 sm:hidden"
                    data-action="toggle-mobile-nav"
                    aria-expanded="false"
                    aria-controls="mobile-nav"
                    aria-label="Mở menu"
                >
                    <i class="fa-solid fa-bars" aria-hidden="true"></i>
                </button>
            </div>

            <form
                action="{{ route('products.index') }}"
                method="get"
                class="w-full min-w-0 sm:max-w-44 md:max-w-52 lg:max-w-60 xl:max-w-72"
            >
                <label for="search" class="sr-only">Tìm kiếm sản phẩm</label>
                <div class="flex gap-1.5">
                    <input
                        id="search"
                        type="search"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Tên hoặc SKU"
                        class="min-w-0 flex-1 rounded-lg border border-gray-300 px-2.5 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                    >
                    <button
                        type="submit"
                        class="inline-flex shrink-0 items-center justify-center rounded-lg bg-blue-600 px-2.5 py-2 text-sm font-medium text-white hover:bg-blue-700 sm:px-3"
                        aria-label="Tìm kiếm"
                    >
                        <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                        <span class="sr-only">Tìm</span>
                    </button>
                </div>
            </form>

            <nav
                id="mobile-nav"
                class="hidden flex-col gap-2 text-sm sm:ml-auto sm:flex sm:shrink-0 sm:flex-row sm:flex-nowrap sm:items-center sm:gap-2 lg:gap-3"
                data-mobile-nav
            >
                <a href="{{ route('home') }}" class="shrink-0 whitespace-nowrap text-gray-700 hover:text-blue-600">Trang chủ</a>
                <a href="{{ route('products.index') }}" class="shrink-0 whitespace-nowrap text-gray-700 hover:text-blue-600">Sản phẩm</a>
                <a
                    href="{{ route('cart.index') }}"
                    data-cart-link
                    class="inline-flex shrink-0 items-center gap-1 whitespace-nowrap text-gray-700 hover:text-blue-600"
                    aria-label="Giỏ hàng, {{ $cartCount ?? 0 }} sản phẩm"
                >
                    <i class="fa-solid fa-cart-shopping" aria-hidden="true"></i>
                    <span>Giỏ (<span data-cart-badge>{{ $cartCount ?? 0 }}</span>)</span>
                </a>
                @auth
                    <a
                        href="{{ route('account.orders.index') }}"
                        class="inline-flex shrink-0 items-center gap-1 whitespace-nowrap text-gray-700 hover:text-blue-600"
                    >
                        <i class="fa-solid fa-receipt" aria-hidden="true"></i>
                        <span>Đơn hàng</span>
                    </a>
                    <a
                        href="{{ route('account.profile.edit') }}"
                        class="inline-flex max-w-28 shrink-0 items-center gap-1 text-gray-700 hover:text-blue-600 lg:max-w-36"
                        title="{{ auth()->user()->name }}"
                    >
                        <i class="fa-solid fa-user shrink-0" aria-hidden="true"></i>
                        <span class="truncate">{{ auth()->user()->name }}</span>
                    </a>
                    <form method="post" action="{{ route('logout') }}" class="inline shrink-0">
                        @csrf
                        <button
                            type="submit"
                            class="inline-flex items-center gap-1 whitespace-nowrap text-gray-700 hover:text-blue-600"
                        >
                            <i class="fa-solid fa-right-from-bracket" aria-hidden="true"></i>
                            <span>Đăng xuất</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-gray-700 hover:text-blue-600">
                        <i class="fa-solid fa-user" aria-hidden="true"></i>
                        <span>Đăng nhập</span>
                    </a>
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-1.5 text-gray-700 hover:text-blue-600">
                        <i class="fa-solid fa-user-plus" aria-hidden="true"></i>
                        <span>Đăng ký</span>
                    </a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div data-flash-container>
            <x-flash-message />
        </div>
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
