@props([
    'title' => null,
    'subtitle' => null,
])

<div {{ $attributes->merge(['class' => 'bg-white shadow-sm ring-1 ring-gray-200 rounded-xl overflow-hidden']) }}>
    @if($title || $subtitle)
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            @if($title)
                <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
            @endif

            @if($subtitle)
                <p class="mt-1 text-sm text-gray-600">{{ $subtitle }}</p>
            @endif
        </div>
    @endif

    <div class="p-6">
        {{ $slot }}
    </div>
</div>