<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <nav class="mb-2 text-sm text-gray-500" aria-label="Fil d'Ariane">
                        <ol class="flex flex-wrap items-center gap-2">
                            <li>
                                <a
                                    href="{{ route('projets.show', $projet) }}"
                                    class="hover:text-gray-700 hover:underline"
                                >
                                    {{ $projet->intitule }}
                                </a>
                            </li>
                            <li aria-hidden="true" class="text-gray-400">/</li>
                            <li class="font-medium text-gray-900">
                                {{ $etape->titre_personnalise ?: ($etape->etapeModele?->libelle ?? 'Étape') }}
                            </li>
                        </ol>
                    </nav>
                    
                    <h2 class="text-xl font-semibold text-gray-900">
                        Étape : {{ $etape->etapeModele?->libelle ?? '—' }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-600">
                        Projet : {{ $projet->intitule }}
                    </p>
                </div>

                <x-ui.button :href="route('projets.show', $projet)" variant="ghost">
                    ← Retour au projet
                </x-ui.button>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- SOUS-ÉTAPES --}}
            <x-ui.card title="Sous-étapes" subtitle="Liste des sous-étapes de cette étape.">
                <div class="mb-4">
                    <a href="{{ route('projets.etapes.create', [$projet->id]) }}?parent_id={{ $etape->id }}"
                       class="inline-flex items-center rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700">
                        + Sous-étape
                    </a>
                </div>

                @if($etape->enfants->isEmpty())
                    <p class="text-sm text-gray-500">Aucune sous-étape pour le moment.</p>
                @else
                    <x-ui.table>
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-center">Ordre</th>
                                <th class="px-4 py-3 text-center">Sous étape</th>
                                <th class="px-4 py-3 text-center">Titre</th>
                                <th class="px-4 py-3 text-center">Statut</th>
                                <th class="p-3 text-center w-40">Actions</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y text-center">
                            @foreach($etape->enfants as $enfant)
                                <tr
                                    class="cursor-pointer hover:bg-gray-50"
                                    data-url="{{ route('projets.etapes.show', [$projet->id, $enfant->id]) }}"
                                >
                                    <td class="px-4 py-3">
                                        {{ $enfant->ordre_reel }}
                                    </td>

                                    <td class="px-4 py-3">
                                        {{ $enfant->etapeModele?->libelle ?? '—' }}
                                    </td>

                                    <td class="px-4 py-3">
                                        {{ $enfant->titre_personnalise ?: '—' }}
                                    </td>

                                    <td class="px-4 py-3">
                                        <span class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-sm text-gray-800">
                                            {{ ucfirst(str_replace('_', ' ', $enfant->statut)) }}
                                        </span>
                                    </td>

                                    <td class="p-3 text-center w-40 align-middle" onclick="event.stopPropagation()">
                                        <select
                                            class="sous-etape-action-select rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            data-edit-url="{{ route('projets.etapes.edit', [$projet->id, $enfant->id]) }}"
                                            data-delete-form-id="delete-sous-etape-{{ $enfant->id }}"
                                        >
                                            <option value="">Actions...</option>
                                            <option value="edit">Modifier</option>
                                            <option value="delete">Supprimer</option>
                                        </select>

                                        <form
                                            id="delete-sous-etape-{{ $enfant->id }}"
                                            method="POST"
                                            action="{{ route('projets.etapes.destroy', [$projet->id, $enfant->id]) }}"
                                            class="hidden"
                                        >
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </x-ui.table>
                @endif
            </x-ui.card>
            {{-- COMMENTAIRES --}}
            <x-ui.card title="Commentaires">
                <form
                    method="POST"
                    action="{{ route('etapes.commentaires.store', [$projet->id, $etape->id]) }}"
                    class="mb-6 space-y-3"
                >
                    @csrf

                    <div>
                        <label for="contenu" class="block text-sm font-medium text-gray-700">
                            Nouveau commentaire
                        </label>
                        <textarea
                            name="contenu"
                            id="contenu"
                            rows="4"
                            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            required
                        >{{ old('contenu') }}</textarea>
                    </div>

                    <div class="mt-3">
                        <button
                            type="submit"
                            class="inline-flex items-center rounded-lg bg-gray-700 hover:bg-gray-800 px-4 py-2 text-sm font-medium text-white"
                        >
                            + commentaire
                        </button>
                    </div>
                </form>

                @if($commentaires->isEmpty())
                    <p class="text-sm text-gray-500">Aucun commentaire pour le moment.</p>
                @else
                    <div class="space-y-4">
                         @foreach($commentaires as $commentaire)
                            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">
                                            {{ $commentaire->user?->name ?? $commentaire->auteur?->name ?? 'Utilisateur inconnu' }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ optional($commentaire->created_at)->format('d/m/Y H:i') }}
                                        </p>
                                        <div class="mt-2 flex flex-wrap gap-2">
                                            @if($commentaire->projet_etape_id)
                                                <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs text-blue-700">
                                                    {{ $commentaire->etape?->parent_id ? 'Sous-étape' : 'Étape' }}
                                                </span>

                                                <span class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs text-gray-700">
                                                    {{ $commentaire->etape?->titre_personnalise ?: ($commentaire->etape?->etapeModele?->libelle ?? 'Étape') }}
                                                </span>
                                            @else
                                                <span class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs text-gray-700">
                                                    Projet
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-3 shrink-0">
                                        @if($commentaire->projet_etape_id === $etape->id && auth()->id() === $commentaire->user_id)
                                            <a href="{{ route('etapes.commentaires.edit', [$projet->id, $etape->id, $commentaire->id]) }}"
                                            class="text-sm text-blue-600 hover:underline">
                                                Modifier
                                            </a>
                                        @endif

                                        @if(
                                            $commentaire->projet_etape_id === $etape->id
                                            && (auth()->id() === $commentaire->user_id || auth()->user()?->estAdmin())
                                        )
                                            <form
                                                method="POST"
                                                action="{{ route('etapes.commentaires.destroy', [$projet->id, $etape->id, $commentaire->id]) }}"
                                                onsubmit="return confirm('Supprimer ce commentaire ?');"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="text-sm text-red-600 hover:underline">
                                                    Supprimer
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>

                                <div class="mt-3 text-sm leading-6 text-gray-700">
                                    {!! nl2br(e($commentaire->contenu)) !!}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </x-ui.card>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const actionSelects = document.querySelectorAll('.sous-etape-action-select');

        actionSelects.forEach(select => {
            select.addEventListener('change', function () {
                const action = this.value;

                if (action === 'edit') {
                    window.location.href = this.dataset.editUrl;
                    return;
                }

                if (action === 'delete') {
                    const ok = confirm('Supprimer cette sous-étape ?');

                    if (ok) {
                        const form = document.getElementById(this.dataset.deleteFormId);
                        if (form) {
                            form.submit();
                            return;
                        }
                    }
                }

                this.value = '';
            });
        });
    });
    document.querySelectorAll('tr[data-url]').forEach(row => {
        row.addEventListener('click', () => {
            window.location = row.dataset.url;
        });
    });
    </script>
</x-app-layout>