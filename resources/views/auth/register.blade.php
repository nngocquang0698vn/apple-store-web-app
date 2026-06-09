@extends('layouts.app')

@section('title', 'Đăng ký - ' . config('app.name'))

@section('content')
    <section class="mx-auto max-w-lg rounded-2xl bg-white p-6 shadow-sm sm:p-8">
        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold text-gray-900">Đăng ký tài khoản</h1>
            <p class="mt-2 text-sm text-gray-600">
                Tạo tài khoản khách hàng để mua sắm và theo dõi đơn hàng.
            </p>
        </div>

        <form method="post" action="{{ route('register.store') }}" class="space-y-5" novalidate>
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Họ tên</label>
                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autocomplete="name"
                    class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 @error('name') border-red-500 @enderror"
                >
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

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
                <label for="phone" class="block text-sm font-medium text-gray-700">Số điện thoại</label>
                <input
                    id="phone"
                    type="tel"
                    name="phone"
                    value="{{ old('phone') }}"
                    required
                    autocomplete="tel"
                    placeholder="0912345678"
                    class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 @error('phone') border-red-500 @enderror"
                >
                @error('phone')
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
                    autocomplete="new-password"
                    class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 @error('password') border-red-500 @enderror"
                >
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Xác nhận mật khẩu</label>
                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                >
            </div>

            <button
                type="submit"
                class="inline-flex w-full items-center justify-center rounded-lg bg-blue-600 px-4 py-3 text-sm font-medium text-white hover:bg-blue-700"
            >
                Đăng ký
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-600">
            Đã có tài khoản?
            <span class="text-gray-400">Đăng nhập (sắp ra mắt)</span>
        </p>
    </section>
@endsection
