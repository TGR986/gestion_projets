<x-app-layout>
    <x-slot name="header">
        <x-ui.page>
             <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">
                        Modifier une étape
                    </h2>
                    <p class="mt-1 text-sm text-gray-600">
                        Mettez à jour les informations de cette étape du projet.
                    </p>
                </div>

                <x-ui.button :href="route('projets.show', $projet)" variant="ghost">
                    ← Retour au projet
                </x-ui.button>
            </div>
        </x-ui.page>
    </x-slot>

    <div class="py-8">
        <x-ui.page size="form">
            <x-ui.card title="Étape du projet : {{ $projet->intitule }}">

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

                <form method="POST" action="{{ route('projets.etapes.update', [$projet, $etape]) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="etape_modele_id" class="block text-sm font-medium text-gray-700">
                            Étape modèle
                        </label>
                        <select
                            name="etape_modele_id"
                            id="etape_modele_id"
                            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                            @foreach($etapesModeles as $modele)
                                <option value="{{ $modele->id }}"
                                    {{ old('etape_modele_id', $etape->etape_modele_id) == $modele->id ? 'selected' : '' }}>
                                    {{ $modele->libelle }}
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
                            value="{{ old('titre_personnalise', $etape->titre_personnalise) }}"
                            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                    </div>

                    <div>
                        <label for="ordre_reel" class="block text-sm font-medium text-gray-700">
                            Ordre
                        </label>
                        <input
                            type="number"
                            name="ordre_reel"
                            id="ordre_reel"
                            value="{{ old('ordre_reel', $etape->ordre_reel) }}"
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
                            @foreach(['a_faire','en_cours','en_attente_validation','validee','refusee','bloquee','cloturee'] as $statut)
                                <option value="{{ $statut }}"
                                    {{ old('statut', $etape->statut) === $statut ? 'selected' : '' }}>
                                    {{ match($statut) {
                                        'a_faire' => 'À faire',
                                        'en_cours' => 'En cours',
                                        'en_attente_validation' => 'En attente de validation',
                                        'validee' => 'Validée',
                                        'refusee' => 'Refusée',
                                        'bloquee' => 'Bloquée',
                                        'cloturee' => 'Clôturée',
                                        default => $statut,
                                    } }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                        <x-ui.button :href="route('projets.show', $projet)" variant="ghost">
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