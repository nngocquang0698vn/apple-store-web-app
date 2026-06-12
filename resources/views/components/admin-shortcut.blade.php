@auth
    @if (auth()->user()->canAccessAdmin())
        <a
            href="{{ route('admin.dashboard') }}"
            class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-5 py-3 text-sm font-medium text-gray-900 shadow-sm transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
        >
            <i class="fa-solid fa-gauge-high" aria-hidden="true"></i>
            Vào khu vực quản trị
        </a>
    @endif
@endauth
