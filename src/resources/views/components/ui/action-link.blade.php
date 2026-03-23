@props([
    'href' => null,
    'variant' => 'default', // default, danger
])

@php
    $classes = match($variant) {
        'danger' => 'text-red-600 hover:text-red-800 font-medium',
        default => 'text-indigo-600 hover:text-indigo-800 font-medium',
    };
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="button" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif