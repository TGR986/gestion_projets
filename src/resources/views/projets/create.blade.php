<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-xl font-semibold text-gray-800">
                Créer un projet
            </h2>

            <a
                href="{{ route('projets.index') }}"
                class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
            >
                ← Retour
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <x-ui.page size="form">
            <x-ui.card title="Nouveau projet">

                @if ($errors->any())
                    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('projets.store') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Code projet</label>
                        <input
                            type="text"
                            name="code_projet"
                            value="{{ old('code_projet') }}"
                            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Intitulé</label>
                        <input
                            type="text"
                            name="intitule"
                            value="{{ old('intitule') }}"
                            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea
                            name="description"
                            rows="4"
                            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Type de projet</label>
                        <input
                            type="text"
                            name="type_projet"
                            value="{{ old('type_projet') }}"
                            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Statut</label>
                        <select
                            name="statut_global"
                            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                            <option value="brouillon" {{ old('statut_global') == 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                            <option value="en_preparation" {{ old('statut_global') == 'en_preparation' ? 'selected' : '' }}>En préparation</option>
                            <option value="en_cours" {{ old('statut_global') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                            <option value="termine" {{ old('statut_global') == 'termine' ? 'selected' : '' }}>Terminé</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                        <x-ui.button :href="route('projets.index')" variant="ghost">
                            Annuler
                        </x-ui.button>

                        <x-ui.button type="submit" variant="success">
                            Créer le projet
                        </x-ui.button>
                    </div>
                </form>

            </x-ui.card>
        </x-ui.page>
    </div>
</x-app-layout>