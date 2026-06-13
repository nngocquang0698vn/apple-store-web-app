@php
    $flashTypes = [
        'success' => 'border-green-200 bg-green-50 text-green-800',
        'error' => 'border-red-200 bg-red-50 text-red-800',
        'warning' => 'border-amber-200 bg-amber-50 text-amber-800',
        'info' => 'border-blue-200 bg-blue-50 text-blue-800',
    ];
@endphp

@foreach ($flashTypes as $type => $classes)
    @if (session($type))
        <div
            class="mb-4 flex items-start gap-3 rounded-lg border px-4 py-3 text-sm {{ $classes }}"
            role="alert"
            data-flash-alert
            data-flash-auto-dismiss
        >
            <p class="min-w-0 flex-1">{{ session($type) }}</p>
            <button
                type="button"
                class="inline-flex shrink-0 rounded-md p-1 opacity-70 transition hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-current focus:ring-offset-1"
                data-action="dismiss-flash"
                aria-label="Đóng thông báo"
            >
                <i class="fa-solid fa-xmark" aria-hidden="true"></i>
            </button>
        </div>
    @endif
@endforeach
