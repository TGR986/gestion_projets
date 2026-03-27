<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">
                Utilisateurs
            </h2>

            <a
                href="{{ route('admin.users.create') }}"
                class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700"
            >
                Créer un utilisateur
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                    Nom
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                    Prénom
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                    Email
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                    Fonction
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                    Structure
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                    Rôle
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                    Statut
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                    Admin
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                    Actions
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($users as $user)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        {{ $user->name }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        {{ $user->prenom ?: '—' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        {{ $user->email }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        {{ $user->fonction ?: '—' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        {{ $user->structure ?: '—' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        @if($user->roles->isNotEmpty())
                                            @foreach($user->roles as $role)
                                                <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-800">
                                                    {{ $role->libelle }}
                                                </span>
                                            @endforeach
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center text-sm">
                                        @if($user->is_active)
                                            <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800">
                                                Actif
                                            </span>
                                        @else
                                            <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-800">
                                                Désactivé
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center text-sm">
                                        @if($user->is_admin)
                                            <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800">
                                                Oui
                                            </span>
                                        @else
                                            <span class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700">
                                                Non
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            {{-- MODIFIER --}}
                                            <a
                                                href="{{ route('admin.users.edit', $user) }}"
                                                class="inline-flex items-center rounded-lg bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700 hover:bg-gray-200"
                                            >
                                                Modifier
                                            </a>

                                            {{-- EMPÊCHER AUTO-DÉSACTIVATION --}}
                                            @if(auth()->id() === $user->id)
                                                <span
                                                    class="inline-flex items-center rounded-lg bg-gray-100 px-3 py-1 text-xs font-medium text-gray-400"
                                                >
                                                    Vous
                                                </span>
                                            @else

                                                {{-- SI ACTIF → bouton désactiver --}}
                                                @if($user->is_active)
                                                    <form method="POST" action="{{ route('admin.users.deactivate', $user) }}">
                                                        @csrf
                                                        @method('PATCH')

                                                        <button
                                                            type="submit"
                                                            class="inline-flex items-center rounded-lg bg-red-100 px-3 py-1 text-xs font-medium text-red-800 hover:bg-red-200"
                                                            onclick="return confirm('Désactiver cet utilisateur ?')"
                                                        >
                                                            Désactiver
                                                        </button>
                                                    </form>
                                                @else
                                                    {{-- SI DÉSACTIVÉ → bouton réactiver --}}
                                                    <form method="POST" action="{{ route('admin.users.reactivate', $user) }}">
                                                        @csrf
                                                        @method('PATCH')

                                                        <button
                                                            type="submit"
                                                            class="inline-flex items-center rounded-lg bg-green-100 px-3 py-1 text-xs font-medium text-green-800 hover:bg-green-200"
                                                        >
                                                            Réactiver
                                                        </button>
                                                    </form>
                                                @endif

                                            @endif

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-4 py-6 text-center text-sm text-gray-500">
                                        Aucun utilisateur trouvé.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>