<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">
                Ajouter un participant
            </h2>

            <a
                href="{{ route('projets.show', $projet) }}"
                class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
            >
                ← Retour
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <x-ui.page size="form">
            <x-ui.card title="Nouveau participant">
                <div class="mb-6 rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-700">
                    <strong>Projet :</strong> {{ $projet->intitule }}
                </div>

                @if ($errors->any())
                    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                        <strong class="block mb-2">Erreurs :</strong>
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('projets.participants.store', $projet) }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="utilisateur_id" class="block text-sm font-medium text-gray-700">
                            Utilisateur
                        </label>
                        <select
                            name="utilisateur_id"
                            id="utilisateur_id"
                            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                            <option value="">-- Choisir un utilisateur --</option>
                            @foreach($utilisateurs as $utilisateur)
                                <option value="{{ $utilisateur->id }}" {{ old('utilisateur_id') == $utilisateur->id ? 'selected' : '' }}>
                                    {{ $utilisateur->name }} ({{ $utilisateur->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="fonction_projet" class="block text-sm font-medium text-gray-700">
                            Fonction dans le projet
                        </label>
                        <input
                            type="text"
                            name="fonction_projet"
                            id="fonction_projet"
                            value="{{ old('fonction_projet') }}"
                            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                    </div>

                    <div class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-3">
                        <label class="inline-flex items-center gap-2 text-sm font-medium text-gray-700">
                            <input
                                type="checkbox"
                                name="est_chef_projet"
                                value="1"
                                {{ old('est_chef_projet') ? 'checked' : '' }}
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500"
                            >
                            <span>Chef de projet</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                        <x-ui.button :href="route('projets.show', $projet)" variant="ghost">
                            Annuler
                        </x-ui.button>

                        <x-ui.button type="submit" variant="success">
                            Ajouter le participant
                        </x-ui.button>
                    </div>
                </form>
            </x-ui.card>
        </x-ui.page>
    </div>
</x-app-layout>