@props(['amount'])

<span {{ $attributes }}>{{ \App\Support\VndMoney::format((int) $amount) }}</span>
