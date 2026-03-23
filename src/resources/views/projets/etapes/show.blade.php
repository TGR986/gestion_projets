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


            {{-- FUTUR : DOCUMENTS --}}
            <x-ui.card title="Documents">
                <div class="text-sm text-gray-500">
                    Zone de gestion des documents (à venir).
                </div>
            </x-ui.card>


        </div>
    </div>
</x-app-layout>