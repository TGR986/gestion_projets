<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">
                    Liste des projets
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    Consultez l’ensemble des projets et accédez à leur suivi détaillé.
                </p>
            </div>

            <x-ui.button :href="route('projets.create')" variant="success">
                + Projet
            </x-ui.button>
        </div>
    </x-slot>

    <div class="py-8">
        <x-ui.page class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <x-ui.card
                title="Projets"
                subtitle="Vue d’ensemble des projets enregistrés dans l’application."
            >
                @if($projets->isEmpty())
                    <div class="rounded-lg border border-dashed border-gray-300 bg-gray-50 px-6 py-10 text-center">
                        <p class="text-sm text-gray-600">Aucun projet auquel vous êtes affecté.</p>

                        <div class="mt-4">
                            <x-ui.button :href="route('projets.create')" variant="primary">
                                Créer un premier projet
                            </x-ui.button>
                        </div>
                    </div>
                @else
                    <x-ui.table>
                        <thead class="bg-gray-50">
                            <tr class="text-center text-xs font-semibold uppercase tracking-wider text-gray-500">
                                <th class="px-4 py-3">Code</th>
                                <th class="px-4 py-3">Intitulé</th>
                                <th class="px-4 py-3 text-left">Description</th>
                                <th class="px-4 py-3">Type</th>
                                <th class="px-4 py-3">Statut</th>
                                <th class="px-4 py-3">Début</th>
                                <th class="px-4 py-3">Fin prévue</th>
                                <th class="px-4 py-3">Créé par</th>
                                <th class="px-4 py-3">Actions</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 bg-white text-center">
                            @foreach($projets as $projet)
                                <tr
                                    class="hover:bg-gray-50 cursor-pointer"
                                    data-href="{{ route('projets.show', $projet) }}"
                                >

                                    <td class="px-4 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        {{ $projet->code_projet }}
                                    </td>

                                    <td class="px-4 py-4 font-medium text-gray-900">
                                        {{ $projet->intitule }}
                                    </td>

                                    <td class="px-4 py-4 text-gray-600 text-left">
                                        <div class="max-w-md">
                                            {{ $projet->description ?: '—' }}
                                        </div>
                                    </td>

                                    <td class="px-4 py-4 whitespace-nowrap">
                                        {{ $projet->type_projet ?: '—' }}
                                    </td>

                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <x-ui.badge :value="$projet->statut_global" />
                                    </td>

                                    <td class="px-4 py-4 whitespace-nowrap">
                                        {{ $projet->date_debut_prevue ?: '—' }}
                                    </td>

                                    <td class="px-4 py-4 whitespace-nowrap">
                                        {{ $projet->date_fin_prevue ?: '—' }}
                                    </td>

                                    <td class="px-4 py-4 whitespace-nowrap">
                                        {{ trim(($projet->createur?->prenom ?? '') . ' ' . ($projet->createur?->name ?? '—')) }}
                                    </td>

                                    <td class="px-4 py-4 text-center min-w-[180px]">
                                        @php
                                            $canManageProjet =
                                                auth()->user() &&
                                                (
                                                    auth()->user()->can('update', $projet) ||
                                                    auth()->user()->can('delete', $projet)
                                                );
                                        @endphp

                                        @if($canManageProjet)
                                            <form method="GET" class="flex justify-center">
                                                <select
                                                    class="rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                    onclick="event.stopPropagation()"
                                                    onchange="
                                                        const value = this.value;
                                                        if (!value) return;

                                                        if (value === 'delete') {
                                                            if (confirm('Supprimer ce projet ?')) {
                                                                this.closest('td').querySelector('.delete-projet-form-{{ $projet->id }}').submit();
                                                            } else {
                                                                this.selectedIndex = 0;
                                                            }
                                                            return;
                                                        }

                                                        window.location.href = value;
                                                    "
                                                >
                                                    <option value="">Options...</option>

                                                    @can('update', $projet)
                                                        <option value="{{ route('projets.edit', $projet) }}">
                                                            Modifier
                                                        </option>
                                                    @endcan

                                                    @can('delete', $projet)
                                                        <option value="delete">
                                                            Supprimer
                                                        </option>
                                                    @endcan
                                                </select>
                                            </form>

                                            @can('delete', $projet)
                                                <form method="POST"
                                                    action="{{ route('projets.destroy', $projet) }}"
                                                    class="delete-projet-form-{{ $projet->id }} hidden"
                                                    onsubmit="event.stopPropagation(); return confirm('Supprimer ce projet ?');">
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

        </x-ui.page>
    </div>
</x-app-layout>