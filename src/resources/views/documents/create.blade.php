<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">
                    Ajouter un document
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    Déposez un document pour cette sous-étape.
                </p>
            </div>

            <x-ui.button :href="route('projets.etapes.show', [$projet, $etape])" variant="ghost">
                ← Retour à la sous-étape
            </x-ui.button>
        </div>
    </x-slot>

    <div class="py-8">
        <x-ui.page size="form">
            <x-ui.card title="Nouveau document">

                @if ($errors->any())
                    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form
                    method="POST"
                    action="{{ route('documents.store', [$projet->id, $etape->id]) }}"
                    enctype="multipart/form-data"
                    class="space-y-6"
                >
                    @csrf

                    <div>
                        <label for="titre" class="block text-sm font-medium text-gray-700">
                            Titre
                        </label>
                        <input
                            type="text"
                            name="titre"
                            id="titre"
                            value="{{ old('titre') }}"
                            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            required
                        >
                    </div>

                    <div>
                        <label for="type_document_id" class="block text-sm font-medium text-gray-700">
                            Type de document
                        </label>

                        <select
                            name="type_document_id"
                            id="type_document_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            required
                        >
                            <option value="">-- Sélectionner un type --</option>

                            @foreach($typesDocuments as $type)
                                <option value="{{ $type->id }}" {{ old('type_document_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->libelle }}
                                </option>
                            @endforeach
                        </select>

                        @error('type_document_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="fichier" class="block text-sm font-medium text-gray-700">
                            Fichier
                        </label>
                        <input
                            type="file"
                            name="fichier"
                            id="fichier"
                            class="mt-1 block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            required
                        >
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                        <x-ui.button :href="route('projets.etapes.show', [$projet, $etape])" variant="ghost">
                            Annuler
                        </x-ui.button>

                        <x-ui.button type="submit" variant="success">
                            Enregistrer
                        </x-ui.button>
                    </div>
                </form>

            </x-ui.card>
        </x-ui.page>
    </div>
</x-app-layout>