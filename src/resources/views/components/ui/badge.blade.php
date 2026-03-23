@props([
    'value' => null,
])

@php
    $label = $value ?? trim(strip_tags($slot));

    $map = [
        'a_faire' => 'bg-gray-100 text-gray-800 ring-gray-200',
        'en_cours' => 'bg-blue-100 text-blue-800 ring-blue-200',
        'en_attente_validation' => 'bg-amber-100 text-amber-800 ring-amber-200',
        'validee' => 'bg-green-100 text-green-800 ring-green-200',
        'refusee' => 'bg-red-100 text-red-800 ring-red-200',
        'bloquee' => 'bg-red-100 text-red-800 ring-red-200',
        'cloturee' => 'bg-slate-100 text-slate-800 ring-slate-200',

        'brouillon' => 'bg-gray-100 text-gray-800 ring-gray-200',
        'soumis' => 'bg-blue-100 text-blue-800 ring-blue-200',
        'en_revision' => 'bg-amber-100 text-amber-800 ring-amber-200',
        'a_corriger' => 'bg-orange-100 text-orange-800 ring-orange-200',
        'archive' => 'bg-slate-100 text-slate-700 ring-slate-200',
    ];

    $pretty = [
        'a_faire' => 'À faire',
        'en_cours' => 'En cours',
        'en_attente_validation' => 'En attente de validation',
        'validee' => 'Validée',
        'refusee' => 'Refusée',
        'bloquee' => 'Bloquée',
        'cloturee' => 'Clôturée',
        'en_revision' => 'En révision',
        'a_corriger' => 'À corriger',
        'archive' => 'Archivé',
    ];

    $badgeClass = $map[$label] ?? 'bg-gray-100 text-gray-800 ring-gray-200';
    $display = $pretty[$label] ?? str_replace('_', ' ', ucfirst((string) $label));
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {$badgeClass}"]) }}>
    {{ $display }}
</span>