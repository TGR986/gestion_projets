<x-app-layout>
    <x-slot name="header">
        <x-ui.page>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">
                        Modifier un projet
                    </h2>
                    <p class="mt-1 text-sm text-gray-600">
                        Mettez à jour les informations du projet.
                    </p>
                </div>

                <x-ui.button :href="route('projets.show', $projet)" variant="ghost">
                    ← Retour
                </x-ui.button>
            </div>
        </x-ui.page>
    </x-slot>

    <div class="py-8">
        <x-ui.page size="form">
            <x-ui.card title="Projet : {{ $projet->intitule }}">

                @if ($errors->any())
                    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                        <div class="font-semibold">Erreurs :</div>
                        <ul class="mt-2 list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('projets.update', $projet) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Code projet
                        </label>
                        <input
                            type="text"
                            name="code_projet"
                            value="{{ old('code_projet', $projet->code_projet) }}"
                            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Intitulé
                        </label>
                        <input
                            type="text"
                            name="intitule"
                            value="{{ old('intitule', $projet->intitule) }}"
                            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Description
                        </label>
                        <textarea
                            name="description"
                            rows="4"
                            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >{{ old('description', $projet->description) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Type de projet
                        </label>
                        <input
                            type="text"
                            name="type_projet"
                            value="{{ old('type_projet', $projet->type_projet) }}"
                            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Statut
                        </label>
                        <select
                            name="statut_global"
                            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                            <option value="brouillon" {{ old('statut_global', $projet->statut_global) == 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                            <option value="en_preparation" {{ old('statut_global', $projet->statut_global) == 'en_preparation' ? 'selected' : '' }}>En préparation</option>
                            <option value="en_cours" {{ old('statut_global', $projet->statut_global) == 'en_cours' ? 'selected' : '' }}>En cours</option>
                            <option value="suspendu" {{ old('statut_global', $projet->statut_global) == 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                            <option value="termine" {{ old('statut_global', $projet->statut_global) == 'termine' ? 'selected' : '' }}>Terminé</option>
                            <option value="archive" {{ old('statut_global', $projet->statut_global) == 'archive' ? 'selected' : '' }}>Archivé</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Date début prévue
                            </label>
                            <input
                                type="date"
                                name="date_debut_prevue"
                                value="{{ old('date_debut_prevue', $projet->date_debut_prevue) }}"
                                class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Date fin prévue
                            </label>
                            <input
                                type="date"
                                name="date_fin_prevue"
                                value="{{ old('date_fin_prevue', $projet->date_fin_prevue) }}"
                                class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Date fin réelle
                            </label>
                            <input
                                type="date"
                                name="date_fin_reelle"
                                value="{{ old('date_fin_reelle', $projet->date_fin_reelle) }}"
                                class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            >
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                        <x-ui.button :href="route('projets.index', $projet)" variant="ghost">
                            Annuler
                        </x-ui.button>

                        <x-ui.button type="submit" variant="success">
                            Enregistrer les modifications
                        </x-ui.button>
                    </div>
                </form>

            </x-ui.card>
        </x-ui.page>
    </div>
</x-app-layout>