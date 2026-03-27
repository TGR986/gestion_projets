<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Modifier l’utilisateur
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">

            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

                <p class="mb-4 text-sm text-gray-600">
                    Utilisateur :
                    <strong>{{ $user->name }} {{ $user->prenom }}</strong>
                </p>

                {{-- ERREURS --}}
                @if ($errors->any())
                    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-4">
                        <ul class="text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                        {{-- Nom --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Nom
                            </label>
                            <input
                                type="text"
                                name="name"
                                value="{{ old('name', $user->name) }}"
                                required
                                class="mt-1 w-full rounded-lg border-gray-300 shadow-sm"
                            >
                        </div>

                        {{-- Prénom --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Prénom
                            </label>
                            <input
                                type="text"
                                name="prenom"
                                value="{{ old('prenom', $user->prenom) }}"
                                class="mt-1 w-full rounded-lg border-gray-300 shadow-sm"
                            >
                        </div>

                        {{-- Fonction --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Fonction
                            </label>
                            <input
                                type="text"
                                name="fonction"
                                value="{{ old('fonction', $user->fonction) }}"
                                class="mt-1 w-full rounded-lg border-gray-300 shadow-sm"
                            >
                        </div>

                        {{-- Structure --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Structure
                            </label>
                            <select
                                name="structure"
                                class="mt-1 w-full rounded-lg border-gray-300 shadow-sm"
                            >
                                <option value="">-- Sélectionner --</option>

                                @foreach($structures as $structure)
                                    <option value="{{ $structure }}"
                                        @selected(old('structure', $user->structure) === $structure)>
                                        {{ $structure }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Email --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">
                                Email
                            </label>
                            <input
                                type="email"
                                name="email"
                                value="{{ old('email', $user->email) }}"
                                required
                                class="mt-1 w-full rounded-lg border-gray-300 shadow-sm"
                            >
                        </div>

                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <a href="{{ route('admin.users.index') }}"
                           class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            Annuler
                        </a>

                        <button
                            type="submit"
                            class="rounded-lg bg-blue-600 px-5 py-2 text-white hover:bg-blue-700"
                        >
                            Enregistrer
                        </button>
                    </div>

                </form>

            </div>

        </div>
    </div>
</x-app-layout>