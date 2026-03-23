<x-app-layout>
    <x-slot name="header">
        <x-ui.page>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">
                        {{ !empty($parentId) ? 'Ajouter une sous-étape' : 'Ajouter une étape' }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-600">
                        Créez une étape ou une sous-étape pour ce projet.
                    </p>
                </div>

                <x-ui.button
                    :href="!empty($parentId)
                        ? route('projets.etapes.show', [$projet, $parentId])
                        : route('projets.show', $projet)"
                    variant="ghost"
                >
                    {{ !empty($parentId) ? '← Retour à l’étape' : '← Retour au projet' }}
                </x-ui.button>
            </div>
        </x-ui.page>
    </x-slot>

    <div class="py-8">
        <x-ui.page size="form">
            <x-ui.card title="Projet : {{ $projet->intitule }}">

                @if (!empty($parentId))
                    <div class="mb-6 rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-800">
                        Vous êtes en train de créer une <strong>sous-étape</strong>.
                    </div>
                @endif

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

                <form method="POST" action="{{ route('projets.etapes.store', $projet) }}" class="space-y-6">
                    @csrf

                    @if(!empty($parentId))
                        <input type="hidden" name="parent_id" value="{{ $parentId }}">
                    @endif

                    <div>
                        <label for="etape_modele_id" class="block text-sm font-medium text-gray-700">
                            {{ !empty($parentId) ? 'Sous-étape modèle' : 'Étape modèle' }}
                        </label>
                        <select
                            name="etape_modele_id"
                            id="etape_modele_id"
                            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                            <option value="">
                                {{ !empty($parentId) ? '-- Choisir une sous-étape --' : '-- Choisir une étape --' }}
                            </option>
                            @foreach($etapesModeles as $etapeModele)
                                <option value="{{ $etapeModele->id }}" {{ old('etape_modele_id') == $etapeModele->id ? 'selected' : '' }}>
                                    {{ $etapeModele->libelle_affiche }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="titre_personnalise" class="block text-sm font-medium text-gray-700">
                            Titre personnalisé
                        </label>
                        <input
                            type="text"
                            name="titre_personnalise"
                            id="titre_personnalise"
                            value="{{ old('titre_personnalise') }}"
                            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                    </div>

                    <div>
                        <label for="statut" class="block text-sm font-medium text-gray-700">
                            Statut
                        </label>
                        <select
                            name="statut"
                            id="statut"
                            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                            <option value="a_faire" {{ old('statut', 'a_faire') == 'a_faire' ? 'selected' : '' }}>À faire</option>
                            <option value="en_cours" {{ old('statut') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                            <option value="en_attente_validation" {{ old('statut') == 'en_attente_validation' ? 'selected' : '' }}>En attente validation</option>
                            <option value="validee" {{ old('statut') == 'validee' ? 'selected' : '' }}>Validée</option>
                            <option value="refusee" {{ old('statut') == 'refusee' ? 'selected' : '' }}>Refusée</option>
                            <option value="bloquee" {{ old('statut') == 'bloquee' ? 'selected' : '' }}>Bloquée</option>
                            <option value="cloturee" {{ old('statut') == 'cloturee' ? 'selected' : '' }}>Clôturée</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                        <x-ui.button
                            :href="!empty($parentId)
                                ? route('projets.etapes.show', [$projet, $parentId])
                                : route('projets.show', $projet)"
                            variant="ghost"
                        >
                            Annuler
                        </x-ui.button>

                        <x-ui.button type="submit" variant="success">
                            {{ !empty($parentId) ? 'Ajouter la sous-étape' : 'Ajouter l’étape' }}
                        </x-ui.button>
                    </div>
                </form>

            </x-ui.card>
        </x-ui.page>
    </div>
</x-app-layout>