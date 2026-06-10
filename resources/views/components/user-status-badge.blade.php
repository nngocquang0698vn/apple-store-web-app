@props(['status'])

@php
    $status = $status instanceof \App\Enums\UserStatus ? $status : \App\Enums\UserStatus::from($status);

    $classes = match ($status) {
        \App\Enums\UserStatus::Active => 'border-green-200 bg-green-50 text-green-800',
        \App\Enums\UserStatus::Blocked => 'border-red-200 bg-red-50 text-red-800',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-medium {$classes}"]) }}>
    {{ $status->label() }}
</span>
