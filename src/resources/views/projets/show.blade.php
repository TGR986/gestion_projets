<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between gap-4">
            <div>
                <nav class="text-sm text-gray-500 mb-2" aria-label="Fil d'Ariane">
                    <ol class="flex flex-wrap items-center gap-2">
                        <li>
                            <a
                                href="{{ route('projets.index') }}"
                                class="hover:text-gray-700 hover:underline"
                            >
                                Projets
                            </a>
                        </li>
                        <li aria-hidden="true" class="text-gray-400">/</li>
                        <li class="font-medium text-gray-900">
                            {{ $projet->intitule }}
                        </li>
                    </ol>
                </nav>

                <h2 class="text-xl font-semibold text-gray-900">
                   Projet : {{ $projet->intitule }}
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    Consultez le projet, ses étapes, ses participants et ses actions de gestion.
                </p>
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                <x-ui.button :href="route('projets.index')" variant="ghost">
                    ← Retour à la liste
                </x-ui.button>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif
                
            @php
                $etapesRacines = $projet->etapes
                    ->whereNull('parent_id')
                    ->sortBy('ordre_reel');
            @endphp

            <x-ui.card
                title="Étapes du projet"
                subtitle="Suivi des étapes principales, sous-étapes et actions de workflow."
            >
                @can('update', $projet)
                    <div class="mb-4 flex flex-wrap gap-2">
                        <x-ui.button :href="route('projets.etapes.create', $projet)" variant="success">
                            + Étape
                        </x-ui.button>
                    </div>
                @endcan

                @if($projet->etapes->isEmpty())
                    <div class="rounded-lg border border-dashed border-gray-300 bg-gray-50 px-6 py-10 text-center">
                        <p class="text-sm text-gray-600">Aucune étape pour le moment.</p>
                    </div>
                @else
                    <x-ui.table>
                        <thead class="bg-gray-50">
                            <tr class="text-xs font-semibold uppercase tracking-wider text-gray-500">
                                <th class="px-4 py-3 text-center">Ordre</th>
                                <th class="px-4 py-3 text-center">Étape</th>
                                <th class="px-4 py-3 text-center">Titre personnalisé</th>
                                <th class="px-4 py-3 text-center">Statut</th>
                                <th class="px-4 py-3 text-center">Date ouverture</th>
                                <th class="px-4 py-3 text-center">Date clôture</th>
                                <th class="px-4 py-3 text-center">Validée par</th>
                                <th class="px-4 py-3 text-center">Actions</th>
                                <th class="px-4 py-3 text-center">Option</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach($etapesRacines as $etape)
                                @php
                                    $etapePrecedente = $projet->etapes
                                        ->whereNull('parent_id')
                                        ->where('ordre_reel', '<', $etape->ordre_reel)
                                        ->sortByDesc('ordre_reel')
                                        ->first();
                                @endphp

                                <tr 
                                    class="hover:bg-gray-50 cursor-pointer align-middle"
                                    data-href="{{ route('projets.etapes.show', [$projet, $etape]) }}"
                                >

                                    <td class="px-4 py-4 text-center font-semibold text-gray-900">
                                        {{ $etape->ordre_reel }}
                                    </td>

                                    <td class="px-4 py-4 text-center text-gray-900">
                                        {{ $etape->etapeModele?->libelle ?? '—' }}
                                    </td>

                                    <td class="px-4 py-4 text-center text-gray-900">
                                        {{ $etape->titre_personnalise ?: '—' }}
                                    </td>

                                   <td class="px-4 py-4 text-center">
                                        <div class="space-y-2">
                                            <x-ui.badge :value="$etape->statut" />

                                            @if($etape->statut === 'refusee' && $etape->motif_refus)
                                                <div class="mx-auto max-w-xs rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-left text-xs text-red-800">
                                                    <span class="font-semibold">Motif :</span><br>
                                                    {{ $etape->motif_refus }}
                                                </div>
                                            @endif
                                        </div>
                                    </td>

                                    <td class="px-4 py-4 text-center text-gray-700">
                                        {{ $etape->date_ouverture ?: '—' }}
                                    </td>

                                    <td class="px-4 py-4 text-center text-gray-700">
                                        {{ $etape->date_cloture ?: '—' }}
                                    </td>

                                    <td class="px-4 py-4 text-center text-gray-700">
                                        {{ $etape->validateur?->name ?? '—' }}
                                    </td>

                                    <td class="px-4 py-4 text-center">
                                        @can('changerStatut', $etape)
                                            @php
                                                $actionsDisponibles = [];

                                                if ($etape->statut === 'a_faire') {
                                                    if (!$etapePrecedente || $etapePrecedente->statut === 'validee') {
                                                        $actionsDisponibles['demarrer'] = 'Démarrer';
                                                    }
                                                }

                                                if ($etape->statut === 'en_cours') {
                                                    $actionsDisponibles['soumettre'] = 'Soumettre';
                                                }

                                                if ($etape->statut === 'en_attente_validation') {
                                                    $actionsDisponibles['valider'] = 'Valider';
                                                    $actionsDisponibles['refuser'] = 'Refuser';
                                                }
                                            @endphp

                                            @if(!empty($actionsDisponibles))
                                                <form
                                                    method="POST"
                                                    action="{{ route('projets.etapes.action', [$projet, $etape]) }}"
                                                    class="inline-block"
                                                    onsubmit="event.stopPropagation();"
                                                >
                                                    @csrf

                                                    <input type="hidden" name="action" class="js-etape-action-input">
                                                    <input type="hidden" name="motif_refus" class="js-etape-motif-input">

                                                    <select
                                                        class="rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 js-etape-action-select"
                                                        data-etape-id="{{ $etape->id }}"
                                                    >
                                                        <option value="">Actions...</option>
                                                        @foreach($actionsDisponibles as $value => $label)
                                                            <option value="{{ $value }}">{{ $label }}</option>
                                                        @endforeach
                                                    </select>
                                                </form>
                                            @else
                                                <span class="text-sm text-gray-400">—</span>
                                            @endif
                                        @else
                                            <span class="text-sm text-gray-400">—</span>
                                        @endcan
                                    </td>

                                    <td class="px-4 py-4 text-center min-w-[220px]">
                                        @php
                                            $canManageEtape =
                                                auth()->user() &&
                                                (
                                                    auth()->user()->can('update', $etape) ||
                                                    auth()->user()->can('delete', $etape) ||
                                                    auth()->user()->can('update', $projet)
                                                );
                                        @endphp


                                        @if($canManageEtape)
                                            <form method="GET" class="flex justify-center">
                                                <select
                                                    class="rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                    onchange="
                                                        const value = this.value;
                                                        if (!value) return;


                                                        if (value === 'delete') {
                                                            if (confirm('Supprimer cette étape ?')) {
                                                                this.closest('td').querySelector('.delete-etape-form-{{ $etape->id }}').submit();
                                                            } else {
                                                                this.selectedIndex = 0;
                                                            }
                                                            return;
                                                        }


                                                        window.location.href = value;
                                                    "
                                                >
                                                    <option value="">Options...</option>                                                    
                                                    @can('update', $etape)
                                                        <option 
                                                            value="{{ route('projets.etapes.edit', [$projet, $etape]) }}">
                                                            Modifier
                                                        </option>
                                                    @endcan                               

                                                    @can('delete', $etape)
                                                        <option value="delete"> Supprimer</option>  
                                                    @endcan
                                                </select>
                                            </form>


                                            @can('delete', $etape)
                                                <form method="POST"
                                                    action="{{ route('projets.etapes.destroy', [$projet, $etape]) }}"
                                                    class="delete-etape-form-{{ $etape->id }} hidden"
                                                    onsubmit="event.stopPropagation(); return confirm ('Supprimer cette étape ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            @endcan
                                        @else
                                            <span class="text-sm text-gray-400">—</span>
                                        @endif
                                    </td>
                                </tr>

                            @endforeach
                        </tbody>
                    </x-ui.table>
                @endif
            </x-ui.card>

            <x-ui.card
                title="Participants du projet"
                subtitle="Liste des utilisateurs affectés à ce projet."
            >
                @can('update', $projet)
                    <div class="mb-4 flex flex-wrap gap-2">
                        <x-ui.button :href="route('projets.participants.create', $projet)" variant="primary">
                            + Participant
                        </x-ui.button>
                    </div>
                @endcan

                @if($projet->participants->isEmpty())
                    <div class="rounded-lg border border-dashed border-gray-300 bg-gray-50 px-6 py-10 text-center">
                        <p class="text-sm text-gray-600">Aucun participant pour le moment.</p>
                    </div>
                @else
                    <x-ui.table>
                        <thead class="bg-gray-50">
                            <tr class="text-xs font-semibold uppercase tracking-wider text-gray-500">
                                <th class="px-4 py-3 text-left">Nom</th>
                                <th class="px-4 py-3 text-left">Email</th>
                                <th class="px-4 py-3 text-left">Fonction</th>
                                <th class="px-4 py-3 text-center">Actions</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach($projet->participants as $participant)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-4 text-gray-900">
                                        {{ $participant->name ?? '—' }}
                                    </td>

                                    <td class="px-4 py-4 text-gray-700">
                                        {{ $participant->email ?? '—' }}
                                    </td>

                                    <td class="px-4 py-4 text-gray-700">
                                        {{ $participant->pivot->fonction_projet ?? '—' }}
                                    </td>

                                    <td class="px-4 py-4 text-center">
                                        @can('update', $projet)
                                            <form
                                                method="POST"
                                                action="{{ route('projets.participants.destroy', [$projet, $participant->pivot->id]) }}"
                                                class="inline-block js-participant-remove-form"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="button"
                                                    class="inline-flex items-center gap-1 text-sm font-medium text-gray-500 hover:text-red-600 js-participant-remove-btn"
                                                >
                                                    Retirer
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-sm text-gray-400">—</span>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </x-ui.table>
                @endif
            </x-ui.card>

            <x-ui.card
                title="Commentaires"
                subtitle="Commentaires liés au projet, aux étapes et aux sous-étapes."
            >
                <form
                    method="POST"
                    action="{{ route('projets.commentaires.store', $projet) }}"
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

                    <div>
                        <button
                            type="submit"
                            class="inline-flex items-center rounded-lg bg-gray-700 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800"
                        >
                            + commentaire
                        </button>
                    </div>
                </form>

                @if($projet->commentaires->isEmpty())
                    <p class="text-sm text-gray-500">Aucun commentaire pour le moment.</p>
                @else
                    <div class="space-y-4">
                        @foreach($commentaires as $commentaire)
                            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">
                                            {{ trim(($commentaire->user?->prenom ?? $commentaire->auteur?->prenom ?? '') . ' ' . ($commentaire->user?->name ?? $commentaire->auteur?->name ?? 'Utilisateur inconnu')) }}
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
    <div
        id="workflow-action-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-4"
    >
        <div class="w-full max-w-md rounded-2xl bg-white shadow-xl p-6">
            <div class="border-b px-6 py-4">
                <h3 id="workflow-action-title" class="text-lg font-semibold text-gray-900">
                    Confirmer l’action
                </h3>
                <p id="workflow-action-text" class="mt-1 text-sm text-gray-600">
                    Voulez-vous confirmer cette action ?
                </p>
            </div>

            <div class="px-6 py-4">
                <div id="workflow-refus-wrapper" class="hidden">
                    <label for="workflow-refus-motif" class="block text-sm font-medium text-gray-700">
                        Motif du refus
                    </label>
                    <textarea
                        id="workflow-refus-motif"
                        rows="5"
                        class="mt-2 w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                        placeholder="Expliquez les motifs du refus..."
                    ></textarea>
                    <p class="mt-2 text-xs text-gray-500">
                        Ce commentaire sera enregistré avec l’étape.
                    </p>
                </div>
            </div>

            <div class="flex justify-end gap-3 border-t px-6 py-4">
                <button
                    type="button"
                    id="workflow-action-cancel"
                    class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                >
                    Annuler
                </button>

                <button
                    type="button"
                    id="workflow-action-confirm"
                    class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
                >
                    Confirmer
                </button>
            </div>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('workflow-action-modal');
        const modalTitle = document.getElementById('workflow-action-title');
        const modalText = document.getElementById('workflow-action-text');
        const refusWrapper = document.getElementById('workflow-refus-wrapper');
        const refusTextarea = document.getElementById('workflow-refus-motif');
        const cancelBtn = document.getElementById('workflow-action-cancel');
        const confirmBtn = document.getElementById('workflow-action-confirm');

        let currentForm = null;
        let currentAction = null;
        let currentSelect = null;
        let currentMode = null; // 'etape' ou 'participant'

        function openModal(config) {
            currentMode = config.mode;
            currentForm = config.form ?? null;
            currentAction = config.action ?? null;
            currentSelect = config.select ?? null;

            modalTitle.textContent = config.title ?? 'Confirmer l’action';
            modalText.textContent = config.text ?? 'Voulez-vous confirmer cette action ?';
            confirmBtn.textContent = config.confirmLabel ?? 'Confirmer';

            refusTextarea.value = '';
            refusWrapper.classList.add('hidden');

            if (config.showRefus === true) {
                refusWrapper.classList.remove('hidden');
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');

            refusTextarea.value = '';

            if (currentSelect) {
                currentSelect.value = '';
            }

            currentForm = null;
            currentAction = null;
            currentSelect = null;
            currentMode = null;

            modalTitle.textContent = 'Confirmer l’action';
            modalText.textContent = 'Voulez-vous confirmer cette action ?';
            confirmBtn.textContent = 'Confirmer';
            refusWrapper.classList.add('hidden');
        }

        // Actions des étapes
        document.querySelectorAll('.js-etape-action-select').forEach(function (select) {
            select.addEventListener('change', function () {
                const action = this.value;

                if (!action) {
                    return;
                }

                let label = action;
                if (action === 'demarrer') label = 'démarrer';
                if (action === 'soumettre') label = 'soumettre';
                if (action === 'valider') label = 'valider';
                if (action === 'refuser') label = 'refuser';

                openModal({
                    mode: 'etape',
                    form: this.closest('form'),
                    action: action,
                    select: this,
                    title: 'Confirmer l’action',
                    text: `Voulez-vous confirmer l’action : ${label} ?`,
                    confirmLabel: 'Confirmer',
                    showRefus: action === 'refuser',
                });
            });
        });

        // Retrait des participants
        document.querySelectorAll('.js-participant-remove-btn').forEach(function (button) {
            button.addEventListener('click', function () {
                openModal({
                    mode: 'participant',
                    form: this.closest('form'),
                    title: 'Retirer le participant',
                    text: 'Voulez-vous vraiment retirer ce participant du projet ?',
                    confirmLabel: 'Retirer',
                    showRefus: false,
                });
            });
        });

        cancelBtn.addEventListener('click', function () {
            closeModal();
        });

        modal.addEventListener('click', function (e) {
            if (e.target === modal) {
                closeModal();
            }
        });

        confirmBtn.addEventListener('click', function () {
            if (!currentForm || !currentMode) {
                closeModal();
                return;
            }

            if (currentMode === 'etape') {
                if (currentAction === 'refuser' && !refusTextarea.value.trim()) {
                    alert('Le motif du refus est obligatoire.');
                    refusTextarea.focus();
                    return;
                }

                currentForm.querySelector('.js-etape-action-input').value = currentAction;
                currentForm.querySelector('.js-etape-motif-input').value = refusTextarea.value.trim();
                currentForm.submit();
                return;
            }

            if (currentMode === 'participant') {
                currentForm.submit();
            }
        });
    });
    </script>
</x-app-layout>