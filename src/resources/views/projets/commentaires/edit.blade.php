@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a
            href="{{ route('projets.show', $projet) }}"
            class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700"
        >
            ← Retour au projet
        </a>
    </div>

    <x-ui.card title="Édition du commentaire">
        <form
            method="POST"
            action="{{ route('projets.commentaires.update', [$projet->id, $commentaire->id]) }}"
            class="space-y-4"
        >
            @csrf
            @method('PUT')

            <div>
                <label for="contenu" class="block text-sm font-medium text-gray-700">
                    Contenu du commentaire
                </label>
                <textarea
                    name="contenu"
                    id="contenu"
                    rows="6"
                    class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    required
                >{{ old('contenu', $commentaire->contenu) }}</textarea>
                @error('contenu')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-2">
                <a
                    href="{{ route('projets.show', $projet) }}"
                    class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"
                >
                    Annuler
                </a>
                <button
                    type="submit"
                    class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
                >
                    Enregistrer
                </button>
            </div>
        </form>
    </x-ui.card>
</div>
@endsection