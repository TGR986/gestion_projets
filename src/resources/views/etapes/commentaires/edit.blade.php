<x-app-layout>
    <x-slot name="header">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-xl font-semibold text-gray-900">
                Modifier le commentaire
            </h2>
            <p class="mt-1 text-sm text-gray-600">
                Sous-étape : {{ $etape->titre_personnalise ?: ($etape->etapeModele?->libelle ?? '—') }}
            </p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-ui.card title="Édition du commentaire">
                <form
                    method="POST"
                    action="{{ route('etapes.commentaires.update', [$projet->id, $etape->id, $commentaire->id]) }}"
                    class="space-y-4"
                >
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="contenu" class="block text-sm font-medium text-gray-700">
                            Contenu
                        </label>
                        <textarea
                            name="contenu"
                            id="contenu"
                            rows="6"
                            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            required
                        >{{ old('contenu', $commentaire->contenu) }}</textarea>
                    </div>

                     <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <a
                            href="{{ route('projets.etapes.show', [$projet->id, $etape->id]) }}"
                            class="text-sm text-gray-600 hover:underline"
                        >
                            ← Annuler
                        </a>

                        <button
                            type="submit"
                            class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
                        >
                            Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </x-ui.card>
        </div>
    </div>
</x-app-layout>