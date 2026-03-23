@props([
    'size' => 'default', // default, form, wide
])

@php
    $sizes = [
        'form' => 'max-w-2xl',
        'default' => 'max-w-5xl',
        'wide' => 'max-w-7xl',
    ];
@endphp

<div {{ $attributes->merge(['class' => ($sizes[$size] ?? $sizes['default']) . ' mx-auto px-4 sm:px-6 lg:px-8']) }}>
    {{ $slot }}
</div>