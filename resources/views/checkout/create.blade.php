@extends('layouts.app')

@section('title', 'Thanh toán - ' . config('app.name'))

@section('content')
    <div data-checkout-page data-summary-url="{{ route('checkout.summary') }}">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Thanh toán</h1>
            <p class="mt-2 text-sm text-gray-600">
                Xác nhận thông tin giao hàng và đặt hàng COD.
            </p>
        </div>

        <div class="grid min-w-0 gap-8 lg:grid-cols-5">
            <section class="min-w-0 lg:col-span-3">
                <form
                    method="post"
                    action="{{ route('checkout.store') }}"
                    class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm"
                    data-action="place-order"
                >
                    @csrf

                    <h2 class="text-lg font-semibold text-gray-900">Thông tin nhận hàng</h2>

                    <div class="mt-6 grid gap-4 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label for="receiver_name" class="block text-sm font-medium text-gray-700">Họ tên người nhận</label>
                            <input
                                id="receiver_name"
                                name="receiver_name"
                                type="text"
                                value="{{ old('receiver_name', $user->name) }}"
                                required
                                class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                            >
                            @error('receiver_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="receiver_phone" class="block text-sm font-medium text-gray-700">Số điện thoại</label>
                            <input
                                id="receiver_phone"
                                name="receiver_phone"
                                type="tel"
                                value="{{ old('receiver_phone', $user->phone) }}"
                                required
                                class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                            >
                            @error('receiver_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="province" class="block text-sm font-medium text-gray-700">Tỉnh / Thành phố</label>
                            <input
                                id="province"
                                name="province"
                                type="text"
                                value="{{ old('province') }}"
                                required
                                class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                            >
                            @error('province')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="district" class="block text-sm font-medium text-gray-700">Quận / Huyện</label>
                            <input
                                id="district"
                                name="district"
                                type="text"
                                value="{{ old('district') }}"
                                required
                                class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                            >
                            @error('district')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="ward" class="block text-sm font-medium text-gray-700">Phường / Xã</label>
                            <input
                                id="ward"
                                name="ward"
                                type="text"
                                value="{{ old('ward') }}"
                                required
                                class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                            >
                            @error('ward')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="address_line" class="block text-sm font-medium text-gray-700">Địa chỉ chi tiết</label>
                            <input
                                id="address_line"
                                name="address_line"
                                type="text"
                                value="{{ old('address_line') }}"
                                required
                                placeholder="Số nhà, tên đường..."
                                class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                            >
                            @error('address_line')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="note" class="block text-sm font-medium text-gray-700">Ghi chú (không bắt buộc)</label>
                            <textarea
                                id="note"
                                name="note"
                                rows="3"
                                class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                            >{{ old('note') }}</textarea>
                            @error('note')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    @error('cart')
                        <p class="mt-4 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-800">{{ $message }}</p>
                    @enderror

                    <div class="mt-6 rounded-lg border border-blue-100 bg-blue-50 px-4 py-3 text-sm text-blue-900">
                        <i class="fa-solid fa-money-bill-wave mr-1" aria-hidden="true"></i>
                        Phương thức thanh toán: <strong>Thanh toán khi nhận hàng (COD)</strong>
                    </div>

                    <div class="mt-6 flex flex-wrap gap-3">
                        <button
                            type="submit"
                            data-checkout-submit
                            @disabled(! $summary['can_checkout'])
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition duration-200 hover:bg-blue-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 disabled:cursor-not-allowed disabled:bg-gray-300"
                        >
                            <i class="fa-solid fa-bag-shopping" aria-hidden="true"></i>
                            <span data-checkout-submit-label>Đặt hàng</span>
                        </button>
                        <a
                            href="{{ route('cart.index') }}"
                            class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-5 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50"
                        >
                            Quay lại giỏ hàng
                        </a>
                        <button
                            type="button"
                            data-action="refresh-checkout-summary"
                            class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50"
                        >
                            <i class="fa-solid fa-rotate" aria-hidden="true"></i>
                            Cập nhật tổng tiền
                        </button>
                    </div>
                </form>
            </section>

            <aside class="min-w-0 lg:col-span-2 lg:sticky lg:top-4 lg:self-start">
                <x-checkout-summary :summary="$summary" />
            </aside>
        </div>
    </div>
@endsection
