@extends('layouts.app')

@section('title', 'Đăng nhập - ' . config('app.name'))

@section('content')
    <section class="mx-auto max-w-lg rounded-2xl bg-white p-6 shadow-sm sm:p-8">
        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold text-gray-900">Đăng nhập</h1>
            <p class="mt-2 text-sm text-gray-600">
                Đăng nhập để mua sắm và theo dõi đơn hàng của bạn.
            </p>
        </div>

        <form method="post" action="{{ route('login.store') }}" class="space-y-5" novalidate>
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autocomplete="email"
                    class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 @error('email') border-red-500 @enderror"
                >
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Mật khẩu</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 @error('password') border-red-500 @enderror"
                >
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-2">
                <input
                    id="remember"
                    type="checkbox"
                    name="remember"
                    value="1"
                    @checked(old('remember'))
                    class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                >
                <label for="remember" class="text-sm text-gray-700">Ghi nhớ đăng nhập</label>
            </div>

            <button
                type="submit"
                class="inline-flex w-full items-center justify-center rounded-lg bg-blue-600 px-4 py-3 text-sm font-medium text-white hover:bg-blue-700"
            >
                Đăng nhập
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-600">
            Chưa có tài khoản?
            <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-700">
                Đăng ký ngay
            </a>
        </p>
    </section>
@endsection
