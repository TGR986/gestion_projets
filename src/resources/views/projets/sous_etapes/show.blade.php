<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between gap-4">
            <div>
                <nav class="mb-2 text-sm text-gray-500">
                    <a href="{{ route('projets.show', $projet->id) }}" class="hover:underline">
                        {{ $projet->nom }}
                    </a>
                    <span class="mx-2">/</span>
                    <a href="{{ route('projets.etapes.show', [$projet->id, $etape->parent->id]) }}" class="hover:underline">
                        {{ $etape->parent->etapeModele?->libelle ?? 'Étape' }}
                    </a>
                    <span class="mx-2">/</span>
                    <span class="text-gray-700">
                        {{ $etape->titre_personnalise ?: ($etape->etapeModele?->libelle ?? 'Sous-étape') }}
                    </span>
                </nav>

                <div class="space-y-1">
                    <h2 class="text-xl font-semibold text-gray-900">
                        Sous-étape : {{ $etape->titre_personnalise ?: ($etape->etapeModele?->libelle ?? '—') }}
                    </h2>
                    <p class="text-m font-medium tracking-wide text-gray-700">
                        Étape : {{ $etape->etapeModele?->libelle ?? '—' }}
                    </p>
                    <p class="text-xs font-medium tracking-wide text-gray-500">
                        Projet : {{ $projet->intitule }}
                    </p>
                </div>
            </div>

            <a href="{{ route('projets.etapes.show', [$projet->id, $etape->parent->id]) }}"
               class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                ← Retour à l’étape
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">

            {{-- INFORMATIONS --}}
            <x-ui.card title="Informations">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm border border-gray-200 rounded-lg overflow-hidden">
                        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                            <tr class="text-center">
                                <th class="px-4 py-3">Étape modèle</th>
                                <th class="px-4 py-3">Titre personnalisé</th>
                                <th class="px-4 py-3">Statut</th>
                                <th class="px-4 py-3">Ordre</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr class="text-center border-t">
                                <td class="px-4 py-3">
                                    {{ $etape->etapeModele?->libelle ?? '—' }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $etape->titre_personnalise ?: '—' }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ ucfirst(str_replace('_', ' ', $etape->statut)) }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $etape->ordre_reel }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </x-ui.card>

            {{-- DOCUMENTS --}}
            <x-ui.card title="Documents">
                <div class="mb-4">
                    <a href="{{ route('documents.create', [$projet->id, $etape->id]) }}"
                       class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                        + document
                    </a>
                </div>

                @if($etape->documents->isEmpty())
                    <p class="text-sm text-gray-500">Aucun document pour cette sous-étape.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm border-separate border-spacing-0">
                            <thead>
                                <tr class="text-center text-xs font-semibold uppercase text-gray-500">
                                    <th class="p-3">Titre</th>
                                    <th class="p-3">Statut</th>
                                    <th class="p-3">Version</th>
                                    <th class="p-3">Fichier</th>
                                    <th class="p-3 w-44">Actions</th>
                                </tr>
                            </thead>

                            <tbody class="align-top">
                                @foreach($etape->documents as $document)
                                    @php
                                        $versions = $document->versions->sortByDesc('numero_version')->values();
                                        $versionSelectionneeParDefaut = $versions->first();
                                    @endphp

                                    <tr class="border-t border-gray-200">
                                        <td class="p-3 text-center font-medium text-gray-900">
                                            {{ $document->titre }}
                                        </td>

                                        <td class="p-3 text-center text-gray-700">
                                            {{ ucfirst(str_replace('_', ' ', $document->statut_document)) }}
                                        </td>

                                        <td class="p-3 text-center">
                                            @if($versions->isNotEmpty())
                                                <select
                                                    class="document-version-select rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                    data-document-id="{{ $document->id }}"
                                                >
                                                    @foreach($versions as $version)
                                                        <option value="{{ $version->id }}">
                                                            v{{ $version->numero_version }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <span class="text-gray-400">—</span>
                                            @endif
                                        </td>

                                        <td class="p-3 text-center">
                                            @if($versionSelectionneeParDefaut)
                                                <a href="{{ route('documents.download', $versionSelectionneeParDefaut->id) }}"
                                                   class="document-main-download-link text-blue-600 hover:underline"
                                                   data-document-id="{{ $document->id }}">
                                                    Télécharger
                                                </a>
                                            @else
                                                <span class="text-gray-400">Aucun fichier</span>
                                            @endif
                                        </td>

                                        <td class="p-3 text-center w-44 align-middle">
                                            <select
                                                class="document-action-select rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                data-create-version-url="{{ route('documents.versions.create', $document->id) }}"
                                                data-delete-form-id="delete-document-{{ $document->id }}"
                                            >
                                                <option value="">Actions...</option>
                                                <option value="add_version">Ajouter une version</option>

                                                @if(auth()->user()?->estAdmin())
                                                    <option value="delete">Supprimer</option>
                                                @endif
                                            </select>

                                            @if(auth()->user()?->estAdmin())
                                                <form
                                                    id="delete-document-{{ $document->id }}"
                                                    method="POST"
                                                    action="{{ route('documents.destroy', $document->id) }}"
                                                    class="hidden"
                                                >
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            @endif
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="5" class="px-3 pb-4">
                                            <div class="rounded-xl border border-gray-100 bg-gray-50 p-4">
                                                <p class="mb-3 text-sm font-semibold text-gray-700">
                                                    Détail de la version sélectionnée
                                                </p>

                                                @forelse($versions as $index => $version)
                                                    <div
                                                        class="document-version-detail {{ $index === 0 ? '' : 'hidden' }}"
                                                        data-document-id="{{ $document->id }}"
                                                        data-version-id="{{ $version->id }}"
                                                        data-download-url="{{ route('documents.download', $version->id) }}"
                                                    >
                                                        <div class="rounded-lg border border-gray-200 bg-white p-3">
                                                            <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                                                                <div class="min-w-0">
                                                                    <div class="flex items-center gap-2">
                                                                        <span class="font-medium text-gray-900">
                                                                            v{{ $version->numero_version }}
                                                                        </span>

                                                                        @if($version->est_version_courante)
                                                                            <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">
                                                                                Courante
                                                                            </span>
                                                                        @endif
                                                                    </div>

                                                                    <div class="mt-1 text-xs text-gray-500">
                                                                        Déposée le {{ optional($version->date_depot)->format('d/m/Y H:i') }}
                                                                        @if($version->deposant)
                                                                            — {{ $version->deposant->name }}
                                                                        @endif
                                                                    </div>

                                                                    @if($version->commentaire_version)
                                                                        <div class="mt-2 text-sm text-gray-600">
                                                                            {{ $version->commentaire_version }}
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <p class="text-sm text-gray-500">
                                                        Aucune version disponible.
                                                    </p>
                                                @endforelse
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </x-ui.card>

            {{-- COMMENTAIRES --}}
            <x-ui.card title="Commentaires">
                <form
                    method="POST"
                    action="{{ route('etapes.commentaires.store', [$projet->id, $etape->id]) }}"
                    class="mb-6 space-y-3"
                >
                    @csrf

                    <div>
                        <label for="contenu" class="block text-sm font-medium text-gray-700">
                            Nouveau commentaire
                        </label>
                        <textarea
                            name="contenu"
                            id="contenu"
                            rows="4"
                            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            required
                        >{{ old('contenu') }}</textarea>
                    </div>

                    <div class="mt-3">
                        <button
                            type="submit"
                            class="inline-flex items-center rounded-lg bg-gray-700 hover:bg-gray-800 px-4 py-2 text-sm font-medium text-white"
                        >
                            + commentaire
                        </button>
                    </div>
                </form>

                @if($etape->commentaires->isEmpty())
                    <p class="text-sm text-gray-500">Aucun commentaire pour le moment.</p>
                @else
                    <div class="space-y-4">
                        @foreach($etape->commentaires as $commentaire)
                            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">
                                            {{ $commentaire->auteur?->name ?? 'Utilisateur inconnu' }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ optional($commentaire->created_at)->format('d/m/Y H:i') }}
                                        </p>
                                    </div>

                                    <div class="flex items-center gap-3 shrink-0">
                                       @if(auth()->id() === $commentaire->user_id)
                                            <a href="{{ route('etapes.commentaires.edit', [$projet->id, $etape->id, $commentaire->id]) }}"
                                            class="text-sm text-blue-600 hover:underline">
                                                Modifier
                                            </a>
                                        @endif

                                        @if(auth()->id() === $commentaire->user_id || auth()->user()?->estAdmin())
                                            <form
                                                method="POST"
                                                action="{{ route('etapes.commentaires.destroy', [$projet->id, $etape->id, $commentaire->id]) }}"
                                                onsubmit="return confirm('Supprimer ce commentaire ?');"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="text-sm text-red-600 hover:underline">
                                                    Supprimer
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>

                                <div class="mt-3 text-sm leading-6 text-gray-700">
                                    {!! nl2br(e($commentaire->contenu)) !!}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </x-ui.card>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const selects = document.querySelectorAll('.document-version-select');

        selects.forEach(select => {
            select.addEventListener('change', function () {
                const documentId = this.dataset.documentId;
                const selectedVersionId = this.value;

                const details = document.querySelectorAll(
                    '.document-version-detail[data-document-id="' + documentId + '"]'
                );

                details.forEach(detail => {
                    if (detail.dataset.versionId === selectedVersionId) {
                        detail.classList.remove('hidden');
                    } else {
                        detail.classList.add('hidden');
                    }
                });

                const selectedDetail = document.querySelector(
                    '.document-version-detail[data-document-id="' + documentId + '"][data-version-id="' + selectedVersionId + '"]'
                );

                const mainDownloadLink = document.querySelector(
                    '.document-main-download-link[data-document-id="' + documentId + '"]'
                );

                if (selectedDetail && mainDownloadLink) {
                    mainDownloadLink.href = selectedDetail.dataset.downloadUrl;
                }
            });
        });

        const documentActionSelects = document.querySelectorAll('.document-action-select');

        documentActionSelects.forEach(select => {
            select.addEventListener('change', function () {
                const action = this.value;

                if (action === 'add_version') {
                    window.location.href = this.dataset.createVersionUrl;
                    return;
                }

                if (action === 'delete') {
                    const ok = confirm('Supprimer ce document et toutes ses versions ?');

                    if (ok) {
                        const form = document.getElementById(this.dataset.deleteFormId);
                        if (form) {
                            form.submit();
                            return;
                        }
                    }
                }

                this.value = '';
            });
        });
    });
    </script>
</x-app-layout>