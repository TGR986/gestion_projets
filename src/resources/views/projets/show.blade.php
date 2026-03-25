<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">
                   Projet : {{ $projet->intitule }}
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    Consultez le projet, ses étapes, ses participants et ses actions de gestion.
                </p>
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                <x-ui.button :href="route('projets.index')" variant="ghost">
                    ← Retour à la liste
                </x-ui.button>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif
                
            @php
                $etapesRacines = $projet->etapes
                    ->whereNull('parent_id')
                    ->sortBy('ordre_reel');
            @endphp

            <x-ui.card
                title="Étapes du projet"
                subtitle="Suivi des étapes principales, sous-étapes et actions de workflow."
            >
                @can('update', $projet)
                    <div class="mb-4 flex flex-wrap gap-2">
                        <x-ui.button :href="route('projets.etapes.create', $projet)" variant="success">
                            + Étape
                        </x-ui.button>
                    </div>
                @endcan

                @if($projet->etapes->isEmpty())
                    <div class="rounded-lg border border-dashed border-gray-300 bg-gray-50 px-6 py-10 text-center">
                        <p class="text-sm text-gray-600">Aucune étape pour le moment.</p>
                    </div>
                @else
                    <x-ui.table>
                        <thead class="bg-gray-50">
                            <tr class="text-xs font-semibold uppercase tracking-wider text-gray-500">
                                <th class="px-4 py-3 text-center">Ordre</th>
                                <th class="px-4 py-3 text-center">Étape modèle</th>
                                <th class="px-4 py-3 text-center">Titre personnalisé</th>
                                <th class="px-4 py-3 text-center">Statut</th>
                                <th class="px-4 py-3 text-center">Date ouverture</th>
                                <th class="px-4 py-3 text-center">Date clôture</th>
                                <th class="px-4 py-3 text-center">Validée par</th>
                                <th class="px-4 py-3 text-center">Actions</th>
                                <th class="px-4 py-3 text-center">Option</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach($etapesRacines as $etape)
                                @php
                                    $etapePrecedente = $projet->etapes
                                        ->whereNull('parent_id')
                                        ->where('ordre_reel', '<', $etape->ordre_reel)
                                        ->sortByDesc('ordre_reel')
                                        ->first();
                                @endphp

                                <tr 
                                    class="hover:bg-gray-50 cursor-pointer align-middle"
                                    data-href="{{ route('projets.etapes.show', [$projet, $etape]) }}"
                                >

                                    <td class="px-4 py-4 text-center font-semibold text-gray-900">
                                        {{ $etape->ordre_reel }}
                                    </td>

                                    <td class="px-4 py-4 text-center text-gray-900">
                                        {{ $etape->etapeModele?->libelle ?? '—' }}
                                    </td>

                                    <td class="px-4 py-4 text-center text-gray-900">
                                        {{ $etape->titre_personnalise ?: '—' }}
                                    </td>

                                    <td class="px-4 py-4 text-center">
                                        <x-ui.badge :value="$etape->statut" />
                                    </td>

                                    <td class="px-4 py-4 text-center text-gray-700">
                                        {{ $etape->date_ouverture ?: '—' }}
                                    </td>

                                    <td class="px-4 py-4 text-center text-gray-700">
                                        {{ $etape->date_cloture ?: '—' }}
                                    </td>

                                    <td class="px-4 py-4 text-center text-gray-700">
                                        {{ $etape->validateur?->name ?? '—' }}
                                    </td>

                                    <td class="px-4 py-4 text-center">
                                        <div class="flex flex-wrap justify-center gap-2">
                                            @can('changerStatut', $etape)
                                                @if($etape->statut === 'a_faire')
                                                    @if(!$etapePrecedente || $etapePrecedente->statut === 'validee')
                                                        <form method="POST" action="{{ route('projets.etapes.action', [$projet, $etape]) }}">
                                                            @csrf
                                                            <input type="hidden" name="action" value="demarrer">
                                                            <x-ui.button type="submit" size="sm" variant="primary">
                                                                Démarrer
                                                            </x-ui.button>
                                                        </form>
                                                    @endif
                                                @endif

                                                @if($etape->statut === 'en_cours')
                                                    <form method="POST" action="{{ route('projets.etapes.action', [$projet, $etape]) }}">
                                                        @csrf
                                                        <input type="hidden" name="action" value="soumettre">
                                                        <x-ui.button type="submit" size="sm" variant="secondary">
                                                            Soumettre
                                                        </x-ui.button>
                                                    </form>
                                                @endif

                                                @if($etape->statut === 'en_attente_validation')
                                                    <form method="POST" action="{{ route('projets.etapes.action', [$projet, $etape]) }}">
                                                        @csrf
                                                        <input type="hidden" name="action" value="valider">
                                                        <x-ui.button type="submit" size="sm" variant="success">
                                                            Valider
                                                        </x-ui.button>
                                                    </form>

                                                    <form method="POST" action="{{ route('projets.etapes.action', [$projet, $etape]) }}">
                                                        @csrf
                                                        <input type="hidden" name="action" value="refuser">
                                                        <x-ui.button type="submit" size="sm" variant="danger">
                                                            Refuser
                                                        </x-ui.button>
                                                    </form>
                                                @endif
                                            @else
                                                <span class="text-sm text-gray-400">—</span>
                                            @endcan
                                        </div>
                                    </td>

                                    <td class="px-4 py-4 text-center min-w-[220px]">
                                        @php
                                            $canManageEtape =
                                                auth()->user() &&
                                                (
                                                    auth()->user()->can('update', $etape) ||
                                                    auth()->user()->can('delete', $etape) ||
                                                    auth()->user()->can('update', $projet)
                                                );
                                        @endphp


                                        @if($canManageEtape)
                                            <form method="GET" class="flex justify-center">
                                                <select
                                                    class="rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                    onchange="
                                                        const value = this.value;
                                                        if (!value) return;


                                                        if (value === 'delete') {
                                                            if (confirm('Supprimer cette étape ?')) {
                                                                this.closest('td').querySelector('.delete-etape-form-{{ $etape->id }}').submit();
                                                            } else {
                                                                this.selectedIndex = 0;
                                                            }
                                                            return;
                                                        }


                                                        window.location.href = value;
                                                    "
                                                >
                                                    <option value="">Options...</option>                                                    
                                                    @can('update', $etape)
                                                        <option 
                                                            value="{{ route('projets.etapes.edit', [$projet, $etape]) }}">
                                                            Modifier
                                                        </option>
                                                    @endcan                               

                                                    @can('delete', $etape)
                                                        <option value="delete"> Supprimer</option>  
                                                    @endcan
                                                </select>
                                            </form>


                                            @can('delete', $etape)
                                                <form method="POST"
                                                    action="{{ route('projets.etapes.destroy', [$projet, $etape]) }}"
                                                    class="delete-etape-form-{{ $etape->id }} hidden"
                                                    onsubmit="event.stopPropagation(); return confirm ('Supprimer cette étape ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            @endcan
                                        @else
                                            <span class="text-sm text-gray-400">—</span>
                                        @endif
                                    </td>
                                </tr>

                            @endforeach
                        </tbody>
                    </x-ui.table>
                @endif
            </x-ui.card>

            <x-ui.card
                title="Participants du projet"
                subtitle="Liste des utilisateurs affectés à ce projet."
            >
                @can('update', $projet)
                    <div class="mb-4 flex flex-wrap gap-2">
                        <x-ui.button :href="route('projets.participants.create', $projet)" variant="primary">
                            + Participant
                        </x-ui.button>
                    </div>
                @endcan

                @if($projet->participants->isEmpty())
                    <div class="rounded-lg border border-dashed border-gray-300 bg-gray-50 px-6 py-10 text-center">
                        <p class="text-sm text-gray-600">Aucun participant pour le moment.</p>
                    </div>
                @else
                    <x-ui.table>
                        <thead class="bg-gray-50">
                            <tr class="text-xs font-semibold uppercase tracking-wider text-gray-500">
                                <th class="px-4 py-3 text-left">Nom</th>
                                <th class="px-4 py-3 text-left">Email</th>
                                <th class="px-4 py-3 text-left">Fonction</th>
                                <th class="px-4 py-3 text-center">Chef de projet</th>
                                <th class="px-4 py-3 text-center">Actif</th>
                                <th class="px-4 py-3 text-center">Actions</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach($projet->participants as $participant)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-4 text-gray-900">
                                        {{ $participant->utilisateur?->name ?? '—' }}
                                    </td>

                                    <td class="px-4 py-4 text-gray-700">
                                        {{ $participant->utilisateur?->email ?? '—' }}
                                    </td>

                                    <td class="px-4 py-4 text-gray-700">
                                        {{ $participant->fonction_projet ?: '—' }}
                                    </td>

                                    <td class="px-4 py-4 text-center">
                                        @if($participant->est_chef_projet)
                                            <x-ui.badge value="validee">Oui</x-ui.badge>
                                        @else
                                            <span class="text-sm text-gray-500">Non</span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-4 text-center">
                                        @if($participant->actif)
                                            <x-ui.badge value="en_cours">Oui</x-ui.badge>
                                        @else
                                            <span class="text-sm text-gray-500">Non</span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-4 text-center">
                                        @can('update', $projet)
                                            <form method="POST"
                                                  action="{{ route('projets.participants.destroy', [$projet, $participant]) }}"
                                                  onsubmit="return confirm('Retirer ce participant ?');">
                                                @csrf
                                                @method('DELETE')

                                                <x-ui.button type="submit" size="sm" variant="danger">
                                                    Retirer
                                                </x-ui.button>
                                            </form>
                                        @else
                                            <span class="text-sm text-gray-400">—</span>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </x-ui.table>
                @endif
            </x-ui.card>
        </div>
    </div>
</x-app-layout>