<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">
                        Étape : {{ $etape->etapeModele?->libelle_affiche }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-600">
                        Projet : {{ $projet->intitule }}
                    </p>
                </div>


                <x-ui.button :href="route('projets.show', $projet)" variant="ghost">
                    ← Retour au projet
                </x-ui.button>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- SOUS-ÉTAPES --}}
            <x-ui.card
                title="Sous-étapes"
                subtitle="Liste des sous-étapes de cette étape."
            >


                <div class="mb-4">
                    <x-ui.button
                        :href="route('projets.etapes.create', ['projet' => $projet->id, 'parent_id' => $etape->id])"
                        variant="success"
                        size="sm"
                    >
                        + Sous-étape
                    </x-ui.button>
                </div>


                @if($etape->enfants->isEmpty())
                    <div class="text-sm text-gray-500 text-center py-6">
                        Aucune sous-étape pour le moment.
                    </div>
                @else
                    <x-ui.table>
                        <thead class="bg-gray-50">
                            <tr class="text-center text-xs font-semibold uppercase text-gray-500">
                                <th class="px-4 py-3">Ordre</th>
                                <th class="px-4 py-3">Étape modèle</th>
                                <th class="px-4 py-3">Titre</th>
                                <th class="px-4 py-3">Statut</th>
                                <th class="p-3 text-center w-40">Actions</th>
                            </tr>
                        </thead>


                        <tbody class="divide-y text-center">
                            @foreach($etape->enfants as $enfant)
                                <tr>


                                    <td class="px-4 py-3">{{ $enfant->ordre_reel }}</td>


                                    <td class="px-4 py-3">
                                        {{ $enfant->etapeModele?->libelle_affiche }}
                                    </td>


                                    <td class="px-4 py-3">
                                        {{ $enfant->titre_personnalise ?: '—' }}
                                    </td>


                                    <td class="px-4 py-3">
                                        <x-ui.badge :value="$enfant->statut" />
                                    </td>

                                    <td class="p-3 text-center w-40 align-middle">
                                        <select
                                            class="sous-etape-action-select rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            data-edit-url="{{ route('projets.etapes.edit', [$projet->id, $enfant->id]) }}"
                                            data-delete-form-id="delete-sous-etape-{{ $enfant->id }}"
                                        >
                                            <option value="">Actions...</option>
                                            <option value="edit">Modifier</option>
                                            <option value="delete">Supprimer</option>
                                        </select>

                                        <form
                                            id="delete-sous-etape-{{ $enfant->id }}"
                                            method="POST"
                                            action="{{ route('projets.etapes.destroy', [$projet->id, $enfant->id]) }}"
                                            class="hidden"
                                        >
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>                              
                                </tr>
                            @endforeach
                        </tbody>
                    </x-ui.table>
                @endif


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
                                <tr class="border-b text-left text-sm font-semibold text-gray-700">
                                    <th class="p-3">Titre</th>
                                    <th class="p-3">Statut</th>
                                    <th class="p-3 text-center">Version</th>
                                    <th class="p-3">Fichier</th>
                                    <th class="p-3 text-center w-40">Actions</th>
                                </tr>
                            </thead>

                            <tbody class="align-top">
                                @foreach($etape->documents as $document)
                                    @php
                                        $versions = $document->versions->sortByDesc('numero_version')->values();
                                        $versionSelectionneeParDefaut = $versions->first();
                                    @endphp

                                    {{-- Ligne principale --}}
                                    <tr class="border-t border-gray-200">
                                        <td class="p-3 font-medium text-gray-900">
                                            {{ $document->titre }}
                                        </td>

                                        <td class="p-3 text-gray-700">
                                            {{ ucfirst(str_replace('_', ' ', $document->statut_document)) }}
                                        </td>

                                        <td class="p-3 text-gray-700 text-center">
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

                                        <td class="p-3">
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

                                        <td class="p-3 text-center">
                                            <details class="relative inline-block text-left">
                                                <summary class="list-none cursor-pointer rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm text-gray-700 shadow-sm hover:bg-gray-50">
                                                    Options...
                                                </summary>

                                                <div class="absolute right-0 z-10 mt-2 w-52 rounded-lg border border-gray-200 bg-white shadow-lg">
                                                    <a href="{{ route('documents.versions.create', $document->id) }}"
                                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                        Ajouter une version
                                                    </a>

                                                    @if(auth()->user()?->estAdmin())
                                                        <form method="POST"
                                                            action="{{ route('documents.destroy', $document->id) }}"
                                                            onsubmit="return confirm('Supprimer ce document et toutes ses versions ?');">
                                                            @csrf
                                                            @method('DELETE')

                                                            <button type="submit"
                                                                    class="block w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50">
                                                                Supprimer
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </details>
                                        </td>
                                    </tr>

                                    {{-- Détail de la version sélectionnée --}}
                                    <tr>
                                        <td colspan="6" class="px-3 pb-4">
                                            <div class="rounded-xl bg-gray-50 p-4 border border-gray-100">
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

                                                                </div>

                                                                <!-- <div class="shrink-0">
                                                                    <a href="{{ route('documents.download', $version->id) }}"
                                                                    class="text-sm font-medium text-blue-600 hover:underline">
                                                                        Télécharger cette version
                                                                    </a>
                                                                </div> -->
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
            

        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // =========================
        // Documents : changement de version
        // =========================
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

        // =========================
        // Sous-étapes : actions via select
        // =========================
        const actionSelects = document.querySelectorAll('.sous-etape-action-select');

        actionSelects.forEach(select => {
            select.addEventListener('change', function () {
                const action = this.value;

                if (action === 'edit') {
                    window.location.href = this.dataset.editUrl;
                    return;
                }

                if (action === 'delete') {
                    const ok = confirm('Supprimer cette sous-étape ?');

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