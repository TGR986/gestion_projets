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
                                <th class="px-4 py-3">Actions</th>
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


                                    <td class="px-4 py-3">
                                        <div class="flex justify-center gap-3">


                                            <x-ui.action-link
                                                :href="route('projets.etapes.edit', [$projet, $enfant])">
                                                Modifier
                                            </x-ui.action-link>


                                            <form method="POST"
                                                action="{{ route('projets.etapes.destroy', [$projet, $enfant]) }}"
                                                onsubmit="return confirm('Supprimer cette sous-étape ?');">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="text-red-600 hover:underline">
                                                    Supprimer
                                                </button>
                                            </form>

                                        </div>
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
                                    <th class="p-3">Commentaires</th>
                                    <th class="p-3">Fichier</th>
                                    <th class="p-3 text-center w-40">Actions</th>
                                </tr>
                            </thead>

                            <tbody class="align-top">
                                @foreach($etape->documents as $document)

                                    {{-- Ligne principale --}}
                                    <tr class="border-t border-gray-200">
                                        <td class="p-3 font-medium text-gray-900">
                                            {{ $document->titre }}
                                        </td>

                                        <td class="p-3 text-gray-700">
                                            {{ ucfirst(str_replace('_', ' ', $document->statut_document)) }}
                                        </td>

                                        <td class="p-3 text-gray-700 text-center">
                                            @if($document->versionCourante)
                                                <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-700">
                                                    v{{ $document->versionCourante->numero_version }}
                                                </span>
                                            @else
                                                <span class="text-gray-400">—</span>
                                            @endif
                                        </td>

                                        <td class="p-3 text-gray-700">
                                            {{ $document->commentaires->count() }}
                                        </td>

                                        <td class="p-3">
                                            @if($document->versionCourante)
                                                <a href="{{ route('documents.download', $document->versionCourante->id) }}"
                                                class="text-blue-600 hover:underline">
                                                    Télécharger
                                                </a>
                                            @else
                                                <span class="text-gray-400">Aucun fichier</span>
                                            @endif
                                        </td>

                                        <td class="p-3 text-center w-40">
                                            <x-ui.button :href="route('documents.versions.create', $document->id)" variant="secondary">
                                                Ajouter une version
                                            </x-ui.button>
                                        </td>
                                    </tr>

                                    {{-- Historique --}}
                                    <tr>
                                        <td colspan="6" class="px-3 pb-4">
                                            <div class="rounded-xl bg-gray-50 p-4 border border-gray-100">
                                                <p class="mb-3 text-sm font-semibold text-gray-700">
                                                    Historique des versions
                                                </p>

                                                <div class="space-y-3">
                                                    @forelse($document->versions as $version)
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

                                                                <div class="shrink-0">
                                                                    <a href="{{ route('documents.download', $version->id) }}"
                                                                    class="text-sm font-medium text-blue-600 hover:underline">
                                                                        Télécharger cette version
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @empty
                                                        <p class="text-sm text-gray-500">
                                                            Aucune version disponible.
                                                        </p>
                                                    @endforelse
                                                </div>
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
</x-app-layout>