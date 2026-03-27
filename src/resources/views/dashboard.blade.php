<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <h1 class="text-2xl font-semibold text-gray-900">Bienvenue</h1>
                <p class="mt-1 text-sm text-gray-600">
                    Accédez rapidement aux principales fonctionnalités.
                </p>
            </div>

            @if(session('success'))
                <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <a
                    href="{{ route('projets.index') }}"
                    class="group block rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:border-blue-300 hover:shadow-md"
                >
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 group-hover:text-blue-700">
                                Projets
                            </h3>
                            <p class="mt-2 text-sm text-gray-600">
                                Consulter, créer et gérer les projets.
                            </p>
                        </div>

                        <div class="rounded-xl bg-blue-50 p-3 text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M6 11h12M6 15h8" />
                            </svg>
                        </div>
                    </div>

                    <div class="mt-6 text-sm font-medium text-blue-600">
                        Ouvrir →
                    </div>
                </a>

                @if(auth()->user()?->isAdmin())
                    <a
                        href="{{ route('admin.users.create') }}"
                        class="group block rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:border-emerald-300 hover:shadow-md"
                    >
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 group-hover:text-emerald-700">
                                    Créer un utilisateur
                                </h3>
                                <p class="mt-2 text-sm text-gray-600">
                                    Ajouter un nouvel utilisateur et lui attribuer un rôle.
                                </p>
                            </div>

                            <div class="rounded-xl bg-emerald-50 p-3 text-emerald-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 3.13a4 4 0 0 1 0 7.75" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 8v6" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M22 11h-6" />
                                </svg>
                            </div>
                        </div>

                        <div class="mt-6 text-sm font-medium text-emerald-600">
                            Ouvrir →
                        </div>
                    </a>
                @endif

                @if(auth()->user()?->isAdmin())
                    <a
                        href="{{ route('admin.users.index') }}"
                        class="group block rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:border-violet-300 hover:shadow-md"
                    >
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 group-hover:text-violet-700">
                                    Utilisateurs
                                </h3>
                                <p class="mt-2 text-sm text-gray-600">
                                    Consulter les utilisateurs et leurs rôles.
                                </p>
                            </div>

                            <div class="rounded-xl bg-violet-50 p-3 text-violet-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5V4H2v16h5" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 10a3 3 0 1 1 6 0a3 3 0 0 1-6 0Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 20v-1a6 6 0 0 1 12 0v1" />
                                </svg>
                            </div>
                        </div>

                        <div class="mt-6 text-sm font-medium text-violet-600">
                            Ouvrir →
                        </div>
                    </a>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>