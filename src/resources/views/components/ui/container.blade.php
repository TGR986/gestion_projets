@props([
    'size' => 'page', // page, form, wide
])

@php
    $sizes = [
        'form' => 'max-w-2xl',
        'page' => 'max-w-5xl',
        'wide' => 'max-w-7xl',
    ];

    $classes = ($sizes[$size] ?? $sizes['page']) . ' mx-auto px-4 sm:px-6 lg:px-8';
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>