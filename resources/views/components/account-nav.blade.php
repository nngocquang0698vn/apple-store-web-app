<nav aria-label="Tài khoản" class="flex flex-wrap gap-2 border-b border-gray-200 pb-4">
    <a
        href="{{ route('account.profile.edit') }}"
        @class([
            'inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium',
            'bg-blue-50 text-blue-700' => request()->routeIs('account.profile.*'),
            'text-gray-600 hover:bg-gray-100 hover:text-gray-900' => ! request()->routeIs('account.profile.*'),
        ])
    >
        <i class="fa-solid fa-user" aria-hidden="true"></i>
        Hồ sơ
    </a>
    <a
        href="{{ route('account.orders.index') }}"
        @class([
            'inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium',
            'bg-blue-50 text-blue-700' => request()->routeIs('account.orders.*'),
            'text-gray-600 hover:bg-gray-100 hover:text-gray-900' => ! request()->routeIs('account.orders.*'),
        ])
    >
        <i class="fa-solid fa-receipt" aria-hidden="true"></i>
        Đơn hàng
    </a>
</nav>
