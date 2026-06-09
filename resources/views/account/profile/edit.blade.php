@extends('layouts.app')

@section('title', 'Hồ sơ tài khoản - ' . config('app.name'))

@section('content')
    <section class="mx-auto max-w-2xl space-y-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Hồ sơ tài khoản</h1>
            <p class="mt-2 text-sm text-gray-600">
                Cập nhật thông tin cá nhân và mật khẩu của bạn.
            </p>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm sm:p-8">
            <h2 class="text-lg font-semibold text-gray-900">Thông tin cá nhân</h2>
            <p class="mt-1 text-sm text-gray-500">
                Email: <span class="font-medium text-gray-700">{{ $user->email }}</span>
            </p>

            <form
                method="post"
                action="{{ route('account.profile.update') }}"
                class="mt-6 space-y-5"
                novalidate
            >
                @csrf
                @method('PATCH')

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Họ tên</label>
                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        required
                        autocomplete="name"
                        class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 @error('name') border-red-500 @enderror"
                    >
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Số điện thoại</label>
                    <input
                        id="phone"
                        type="tel"
                        name="phone"
                        value="{{ old('phone', $user->phone) }}"
                        required
                        autocomplete="tel"
                        class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 @error('phone') border-red-500 @enderror"
                    >
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="default_address" class="block text-sm font-medium text-gray-700">Địa chỉ mặc định</label>
                    <textarea
                        id="default_address"
                        name="default_address"
                        rows="3"
                        autocomplete="street-address"
                        placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh/thành phố"
                        class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 @error('default_address') border-red-500 @enderror"
                    >{{ old('default_address', $user->default_address) }}</textarea>
                    @error('default_address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-3 text-sm font-medium text-white hover:bg-blue-700"
                >
                    Lưu thông tin
                </button>
            </form>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm sm:p-8">
            <h2 class="text-lg font-semibold text-gray-900">Đổi mật khẩu</h2>
            <p class="mt-1 text-sm text-gray-500">
                Nhập mật khẩu hiện tại để xác nhận thay đổi.
            </p>

            <form
                method="post"
                action="{{ route('account.password.update') }}"
                class="mt-6 space-y-5"
                novalidate
            >
                @csrf
                @method('PATCH')

                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700">Mật khẩu hiện tại</label>
                    <input
                        id="current_password"
                        type="password"
                        name="current_password"
                        required
                        autocomplete="current-password"
                        class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 @error('current_password') border-red-500 @enderror"
                    >
                    @error('current_password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Mật khẩu mới</label>
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
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Xác nhận mật khẩu mới</label>
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
                    class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50"
                >
                    Đổi mật khẩu
                </button>
            </form>
        </div>
    </section>
@endsection
