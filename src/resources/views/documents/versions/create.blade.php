<x-app-layout>
    <x-slot name="header">
        <x-ui.page>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">
                        Ajouter une nouvelle version
                    </h2>
                    <p class="mt-1 text-sm text-gray-600">
                        Document : {{ $document->titre }}
                    </p>
                </div>

                <x-ui.button :href="route('projets.etapes.show', [$document->projet_id, $document->projet_etape_id])" variant="ghost">
                    ← Retour à la sous-étape
                </x-ui.button>
            </div>
        </x-ui.page>
    </x-slot>

    <div class="py-8">
        <x-ui.page size="form">
            <x-ui.card title="Nouvelle version">

                @if ($errors->any())
                    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if($versionCourante)
                    <div class="mb-6 rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-800">
                        Version courante : v{{ $versionCourante->numero_version }}
                        @if($versionCourante->nom_fichier_original)
                            — {{ $versionCourante->nom_fichier_original }}
                        @endif
                    </div>
                @endif

                <form
                    method="POST"
                    action="{{ route('documents.versions.store', $document->id) }}"
                    enctype="multipart/form-data"
                    class="space-y-6"
                >
                    @csrf

                    <div>
                        <label for="fichier" class="block text-sm font-medium text-gray-700">
                            Nouveau fichier
                        </label>
                        <input
                            type="file"
                            name="fichier"
                            id="fichier"
                            class="mt-1 block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            required
                        >
                    </div>

                    <div>
                        <label for="commentaire_version" class="block text-sm font-medium text-gray-700">
                            Commentaire de version
                        </label>
                        <textarea
                            name="commentaire_version"
                            id="commentaire_version"
                            rows="4"
                            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >{{ old('commentaire_version') }}</textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                        <x-ui.button :href="route('projets.etapes.show', [$document->projet_id, $document->projet_etape_id])" variant="ghost">
                            Annuler
                        </x-ui.button>

                        <x-ui.button type="submit" variant="success">
                            Ajouter la version
                        </x-ui.button>
                    </div>
                </form>

            </x-ui.card>
        </x-ui.page>
    </div>
</x-app-layout>