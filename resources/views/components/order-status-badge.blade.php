@props(['status'])

@php
    $status = $status instanceof \App\Enums\OrderStatus ? $status : \App\Enums\OrderStatus::from($status);

    $classes = match ($status) {
        \App\Enums\OrderStatus::Pending => 'border-amber-200 bg-amber-50 text-amber-800',
        \App\Enums\OrderStatus::Confirmed => 'border-blue-200 bg-blue-50 text-blue-800',
        \App\Enums\OrderStatus::Shipping => 'border-indigo-200 bg-indigo-50 text-indigo-800',
        \App\Enums\OrderStatus::Completed => 'border-green-200 bg-green-50 text-green-800',
        \App\Enums\OrderStatus::Cancelled => 'border-gray-200 bg-gray-100 text-gray-700',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-medium {$classes}"]) }}>
    {{ $status->label() }}
</span>
