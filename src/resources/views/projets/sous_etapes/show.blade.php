<x-app-layout>
    <x-slot name="header">
        <div class="w-full flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

            <div class="flex flex-col gap-2">
                <nav class="text-sm text-gray-500" aria-label="Fil d'Ariane">
                    <ol class="flex flex-wrap items-center gap-2">
                        <li>
                            <a
                                href="{{ route('projets.show', $projet) }}"
                                class="hover:text-gray-700 hover:underline"
                            >
                                {{ $projet->intitule }}
                            </a>
                        </li>

                        @if($etape->parent)
                            <li aria-hidden="true" class="text-gray-400">/</li>
                            <li>
                                <a
                                    href="{{ route('projets.etapes.show', [$projet->id, $etape->parent->id]) }}"
                                    class="hover:text-gray-700 hover:underline"
                                >
                                    {{ $etape->parent->titre_personnalise ?: ($etape->parent->etapeModele?->libelle ?? 'Étape') }}
                                </a>
                            </li>
                        @endif

                        <li aria-hidden="true" class="text-gray-400">/</li>
                        <li class="font-medium text-gray-900">
                            {{ $etape->titre_personnalise ?: ($etape->etapeModele?->libelle ?? 'Sous-étape') }}
                        </li>
                    </ol>
                </nav>

                <div class="flex flex-col gap-1">
                    <h2 class="text-xl font-semibold text-gray-900">
                        Sous-étape :
                        {{ $etape->titre_personnalise ?: ($etape->etapeModele?->libelle ?? '—') }}
                    </h2>

                    <p class="text-sm text-gray-600">
                        Projet : {{ $projet->intitule }}
                    </p>
                </div>
            </div>

            <div class="sm:ml-auto">
                @if($etape->parent)
                    <a
                        href="{{ route('projets.etapes.show', [$projet->id, $etape->parent->id]) }}"
                        class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                    >
                        ← Retour à l’étape
                    </a>
                @else
                    <a
                        href="{{ route('projets.show', $projet) }}"
                        class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                    >
                        ← Retour au projet
                    </a>
                @endif
            </div>

        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">

            {{-- INFORMATIONS --}}
            <x-ui.card title="Informations">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">

                    <div>
                        <div class="text-gray-500">Sous étape</div>
                        <div class="font-medium">
                            {{ $etape->etapeModele?->libelle ?? '—' }}
                        </div>
                    </div>

                    <div>
                        <div class="text-gray-500">Titre personnalisé</div>
                        <div class="font-medium">
                            {{ $etape->titre_personnalise ?: '—' }}
                        </div>
                    </div>

                    <div>
                        <div class="text-gray-500">Statut</div>
                        <div class="font-medium">
                            {{ ucfirst(str_replace('_', ' ', $etape->statut)) }}
                        </div>
                    </div>

                    <div>
                        <div class="text-gray-500">Ordre</div>
                        <div class="font-medium">
                            {{ $etape->ordre_reel }}
                        </div>
                    </div>

                </div>
            </x-ui.card>

            {{-- DOCUMENTS --}}
            <x-ui.card title="Documents">
               <div class="mb-4 flex justify-start">
                    <a href="{{ route('documents.create', [$projet->id, $etape->id]) }}"
                    class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                        + document
                    </a>
                </div>

                @if($etape->documents->isEmpty())
                    <p class="text-sm text-gray-500">Aucun document pour cette sous-étape.</p>
                @else
                    @php
                        $premierDocumentAvecVersion = $etape->documents->first(function ($doc) {
                            return $doc->versions->isNotEmpty();
                        });

                        $premiereVersionParDefaut = $premierDocumentAvecVersion
                            ? $premierDocumentAvecVersion->versions->sortByDesc('numero_version')->first()
                            : null;

                        $validationsParDefaut = $premiereVersionParDefaut
                            ? $premiereVersionParDefaut->validations
                            : collect();
                    @endphp

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm border-separate border-spacing-0">
                            <thead>
                                <tr class="text-center text-xs font-semibold uppercase text-gray-500">
                                    <th class="p-3 text-left text-xs sm:text-sm">Titre</th>
                                    <th class="p-3 text-xs sm:text-sm">Version</th>
                                    <th class="p-3 text-xs sm:text-sm">Fichier</th>
                                    <th class="p-3 w-56 text-xs sm:text-sm">Validation</th>
                                    <th class="p-3 w-44 text-xs sm:text-sm">Actions</th>
                                </tr>
                            </thead>

                            <tbody class="align-top">
                                @foreach($etape->documents as $document)
                                    @php
                                        $versions = $document->versions->sortByDesc('numero_version')->values();
                                        $versionSelectionneeParDefaut = $versions->first();
                                    @endphp

                                    <tr
                                        class="border-t border-gray-200 hover:bg-gray-50"
                                        data-document-row="{{ $document->id }}"
                                    >
                                        <td class="p-3 text-xs sm:text-sm text-left font-medium text-gray-900">
                                            {{ $document->titre }}
                                        </td>

                                        <td class="p-3 text-xs sm:text-sm text-center">
                                            @if($versions->isNotEmpty() && $versionSelectionneeParDefaut)
                                                <select
                                                    class="w-full sm:w-auto rounded-lg document-version-select rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                    data-document-id="{{ $document->id }}"
                                                >
                                                    @foreach($versions as $version)
                                                        <option
                                                            value="{{ $version->id }}"
                                                            @selected(
                                                                $premiereVersionParDefaut &&
                                                                $version->id === $premiereVersionParDefaut->id
                                                            )
                                                        >
                                                            v{{ $version->numero_version }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <span class="text-gray-400">—</span>
                                            @endif
                                        </td>

                                        <td class="p-3 text-xs sm:text-sm text-center">
                                            @if($versionSelectionneeParDefaut)
                                                <a href="{{ route('documents.download', $versionSelectionneeParDefaut->id) }}"
                                                class="document-main-download-link text-blue-600 hover:underline"
                                                data-document-id="{{ $document->id }}">
                                                    Télécharger
                                                </a>
                                            @else
                                                <span class="text-gray-400">Aucun fichier</span>
                                            @endif
                                        </td>

                                        <td class="p-3 text-xs sm:text-sm text-center w-56 align-middle">
                                            @if(auth()->user()?->estAdmin() && $versionSelectionneeParDefaut)
                                                <form
                                                    method="POST"
                                                    action="{{ route('documents.versions.validation', $versionSelectionneeParDefaut->id) }}"
                                                    class="inline-block js-document-validation-form"
                                                    data-document-id="{{ $document->id }}"
                                                    data-validation-route-template="{{ route('documents.versions.validation', '__VERSION_ID__') }}"
                                                >
                                                    @csrf

                                                    <input type="hidden" name="validation_action" class="js-document-validation-input">
                                                    <input type="hidden" name="commentaire_validation" class="js-document-validation-comment-input">

                                                    <select
                                                        class="w-full sm:w-auto rounded-lg document-validation-select rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                        data-document-id="{{ $document->id }}"
                                                    >
                                                        <option value="">Validation...</option>
                                                        <option value="validation_technique">Validation technique</option>
                                                        <option value="validation_administrative">Validation administrative</option>
                                                        <option value="validation_financiere">Validation financière</option>
                                                        <option value="refus">Refus</option>
                                                    </select>
                                                </form>
                                            @else
                                                <span class="text-sm text-gray-400">—</span>
                                            @endif
                                        </td>

                                        <td class="p-3 text-xs sm:text-sm text-center w-44 align-middle">
                                            <select
                                                class="document-action-select rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                data-create-version-url="{{ route('documents.versions.create', $document->id) }}"
                                                data-delete-form-id="delete-document-{{ $document->id }}"
                                            >
                                                <option value="">Actions...</option>
                                                <option value="add_version">Ajouter une version</option>

                                                @if(auth()->user()?->estAdmin())
                                                    <option value="delete">Supprimer</option>
                                                @endif
                                            </select>

                                            @if(auth()->user()?->estAdmin())
                                                <form
                                                    id="delete-document-{{ $document->id }}"
                                                    method="POST"
                                                    action="{{ route('documents.destroy', $document->id) }}"
                                                    class="hidden"
                                                >
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Panneau unique de détail --}}
                   <div class="mt-6 rounded-xl border border-gray-200 bg-gray-50 p-4 sm:p-6">
                        <div class="mb-3 flex items-center justify-between gap-3">
                            <div>
                                <p class="text-sm font-semibold text-gray-800">
                                    Détail de la version sélectionnée
                                </p>
                            </div>
                        </div>

                        <div id="document-detail-panel">
                            @if($premiereVersionParDefaut && $premierDocumentAvecVersion)
                                <div class="rounded-lg border border-gray-200 bg-white p-4">
                                    <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-2">
                                                <span id="detail-version-number" class="font-medium text-gray-900">
                                                    v{{ $premiereVersionParDefaut->numero_version }}
                                                </span>

                                                <span
                                                    id="detail-version-current-badge"
                                                    class="{{ $premiereVersionParDefaut->est_version_courante ? '' : 'hidden' }} inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700"
                                                >
                                                    Courante
                                                </span>
                                            </div>

                                            <div id="detail-version-meta" class="mt-1 text-xs text-gray-500">
                                                Déposée le {{ optional($premiereVersionParDefaut->date_depot)->format('d/m/Y H:i') }}
                                                @if($premiereVersionParDefaut->deposant)
                                                    — {{ $premiereVersionParDefaut->deposant->name }}
                                                @endif
                                            </div>

                                            <div
                                                id="detail-version-comment"
                                                class="{{ $premiereVersionParDefaut->commentaire_version ? '' : 'hidden' }} mt-2 text-sm text-gray-600"
                                            >
                                                {{ $premiereVersionParDefaut->commentaire_version }}
                                            </div>
                                        </div>
                                    </div>

                                <div
                                    id="detail-validation-block"
                                    class="{{ $validationsParDefaut->isNotEmpty() ? '' : 'hidden' }} mt-3 border-t pt-3"
                                >
                                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                                        Validations
                                    </p>

                                    <div id="detail-validation-list" class="space-y-3">
                                        @forelse($validationsParDefaut as $validation)
                                            <div class="space-y-2">
                                                <div class="flex flex-wrap items-center gap-2 text-sm text-gray-700">
                                                    <x-ui.badge :value="$validation->type_validation" />

                                                    <span>
                                                        le {{ optional($validation->date_validation)?->setTimezone('Pacific/Wallis')->format('d/m/Y H:i') }}

                                                        @if($validation->validateur)
                                                            par
                                                            {{ trim(($validation->validateur->prenom ?? '') . ' ' . ($validation->validateur->name ?? '')) }}
                                                            @if($validation->validateur->structure)
                                                                ({{ $validation->validateur->structure }})
                                                            @endif
                                                        @endif
                                                    </span>
                                                </div>

                                                @if(!empty($validation->commentaire))
                                                    <div class="rounded-lg bg-gray-50 px-3 py-2 text-sm text-gray-700">
                                                        {{ $validation->commentaire }}
                                                    </div>
                                                @endif
                                            </div>
                                        @empty
                                            <div class="rounded-lg border border-dashed border-gray-300 bg-gray-50 px-4 py-4 text-sm text-gray-500">
                                                Aucune validation enregistrée.
                                            </div>
                                        @endforelse
                                    </div>  
                                </div>
                            @else
                                <div class="rounded-lg border border-dashed border-gray-300 bg-white px-4 py-8 text-center text-sm text-gray-500">
                                    Aucune version disponible pour le moment.
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Référentiel des détails --}}
                    <div class="hidden">
                        @foreach($etape->documents as $document)
                            @php
                                $versions = $document->versions->sortByDesc('numero_version')->values();
                            @endphp

                            @foreach($versions as $version)
                                @php
                                    $validationsJson = $version->validations->map(function ($validation) {
                                        return [
                                            'type' => $validation->type_validation,
                                            'date' => optional($validation->date_validation)?->setTimezone('Pacific/Wallis')->format('d/m/Y H:i'),
                                            'commentaire' => $validation->commentaire,
                                            'validateur_prenom' => $validation->validateur?->prenom,
                                            'validateur_nom' => $validation->validateur?->name,
                                            'validateur_structure' => $validation->validateur?->structure,
                                        ];
                                    })->values()->toJson();
                                @endphp

                                <div
                                    class="document-version-detail-data"
                                    data-document-id="{{ $document->id }}"
                                    data-document-title="{{ $document->titre }}"
                                    data-document-label="Document #{{ $document->id }}"
                                    data-version-id="{{ $version->id }}"
                                    data-version-number="v{{ $version->numero_version }}"
                                    data-version-current="{{ $version->est_version_courante ? '1' : '0' }}"
                                    data-version-meta="Déposée le {{ optional($version->date_depot)->format('d/m/Y H:i') }}@if($version->deposant) — {{ $version->deposant->name }}@endif"
                                    data-version-comment="{{ $version->commentaire_version ?? '' }}"
                                    data-validations='{{ $validationsJson }}'
                                    data-download-url="{{ route('documents.download', $version->id) }}"
                                ></div>
                            @endforeach
                        @endforeach
                    </div>
                @endif
            </x-ui.card>

            {{-- COMMENTAIRES --}}
            <x-ui.card title="Commentaires" subtitle="Échanges liés à cette sous-étape.">
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
                                            <a
                                                href="{{ route('etapes.commentaires.edit', [$projet->id, $etape->id, $commentaire->id]) }}"
                                                class="text-sm text-blue-600 hover:underline"
                                            >
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

    <div
        id="document-validation-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-4"
    >
        <div class="w-full max-w-md mx-auto rounded-2xl bg-white shadow-xl">
            <div class="border-b px-6 py-4">
                <h3 id="document-validation-title" class="text-lg font-semibold text-gray-900">
                    Confirmer la validation
                </h3>
                <p id="document-validation-text" class="mt-1 text-sm text-gray-600">
                    Voulez-vous confirmer cette action ?
                </p>
            </div>

            <div class="px-6 py-4">
                <div id="document-refus-wrapper" class="hidden">
                    <label for="document-refus-commentaire" class="block text-sm font-medium text-gray-700">
                        Commentaire du refus
                    </label>
                    <textarea
                        id="document-refus-commentaire"
                        rows="5"
                        class="mt-2 w-full rounded-lg border-gray-300 text-sm"
                        placeholder="Expliquez les motifs du refus..."
                    ></textarea>
                    <p class="mt-2 text-xs text-gray-500">
                        Ce commentaire sera enregistré avec le document.
                    </p>
                </div>
            </div>

            <div class="flex justify-end gap-3 border-t px-6 py-4">
                <button
                    type="button"
                    id="document-validation-cancel"
                    class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                >
                    Annuler
                </button>

                <button
                    type="button"
                    id="document-validation-confirm"
                    class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
                >
                    Confirmer
                </button>
            </div>
        </div>
    </div>

   <script>
    document.addEventListener('DOMContentLoaded', function () {
        const detailDocumentTitle = document.getElementById('detail-document-title');
        const detailVersionNumber = document.getElementById('detail-version-number');
        const detailVersionCurrentBadge = document.getElementById('detail-version-current-badge');
        const detailVersionMeta = document.getElementById('detail-version-meta');
        const detailVersionComment = document.getElementById('detail-version-comment');
        const detailValidationBlock = document.getElementById('detail-validation-block');
        const detailValidationList = document.getElementById('detail-validation-list');
        const detailValidationCommentBox = document.getElementById('detail-validation-comment-box');
        const detailValidationComment = document.getElementById('detail-validation-comment');

        const documentValidationModal = document.getElementById('document-validation-modal');
        const documentValidationTitle = document.getElementById('document-validation-title');
        const documentValidationText = document.getElementById('document-validation-text');
        const documentRefusWrapper = document.getElementById('document-refus-wrapper');
        const documentRefusCommentaire = document.getElementById('document-refus-commentaire');
        const documentValidationCancel = document.getElementById('document-validation-cancel');
        const documentValidationConfirm = document.getElementById('document-validation-confirm');

        let currentValidationForm = null;
        let currentValidationAction = null;
        let currentValidationSelect = null;

        function formatValidationType(value) {
            if (!value) {
                return '';
            }

            return value
                .replaceAll('_', ' ')
                .replace(/\b\w/g, function (char) {
                    return char.toUpperCase();
                });
        }

        function getSelectedVersionIdForDocument(documentId) {
            const select = document.querySelector(
                '.document-version-select[data-document-id="' + documentId + '"]'
            );

            if (select && select.value) {
                return select.value;
            }

            const firstVersionData = document.querySelector(
                '.document-version-detail-data[data-document-id="' + documentId + '"]'
            );

            return firstVersionData ? firstVersionData.dataset.versionId : null;
        }

        function updateDetailPanel(documentId, versionId) {
            const source = document.querySelector(
                '.document-version-detail-data[data-document-id="' + documentId + '"][data-version-id="' + versionId + '"]'
            );

            if (!source) {
                return;
            }

            if (detailDocumentTitle) {
                detailDocumentTitle.textContent =
                    source.dataset.documentTitle + ' — ' + source.dataset.documentLabel;
            }

            if (detailVersionNumber) {
                detailVersionNumber.textContent = source.dataset.versionNumber || '';
            }

            if (detailVersionCurrentBadge) {
                if (source.dataset.versionCurrent === '1') {
                    detailVersionCurrentBadge.classList.remove('hidden');
                } else {
                    detailVersionCurrentBadge.classList.add('hidden');
                }
            }

            if (detailVersionMeta) {
                detailVersionMeta.textContent = source.dataset.versionMeta || '';
            }

            if (detailVersionComment) {
                if ((source.dataset.versionComment || '').trim() !== '') {
                    detailVersionComment.textContent = source.dataset.versionComment;
                    detailVersionComment.classList.remove('hidden');
                } else {
                    detailVersionComment.textContent = '';
                    detailVersionComment.classList.add('hidden');
                }
            }

            if (detailValidationBlock) {
                let validations = [];

                try {
                    validations = JSON.parse(source.dataset.validations || '[]');
                } catch (e) {
                    validations = [];
                }

                if (Array.isArray(validations) && validations.length > 0) {
                    detailValidationList.innerHTML = renderValidationList(validations);
                    detailValidationBlock.classList.remove('hidden');
                } else {
                    detailValidationList.innerHTML = '';
                    detailValidationBlock.classList.add('hidden');
                }
            }

            const mainDownloadLink = document.querySelector(
                '.document-main-download-link[data-document-id="' + documentId + '"]'
            );

            if (mainDownloadLink && source.dataset.downloadUrl) {
                mainDownloadLink.href = source.dataset.downloadUrl;
            }

            const validationForm = document.querySelector(
                '.js-document-validation-form[data-document-id="' + documentId + '"]'
            );

            if (validationForm) {
                const template = validationForm.dataset.validationRouteTemplate || '';
                if (template) {
                    validationForm.action = template.replace('__VERSION_ID__', versionId);
                }
            }
        }

        document.querySelectorAll('.document-version-select').forEach(function (select) {
            select.addEventListener('change', function () {
                updateDetailPanel(this.dataset.documentId, this.value);
            });
        });

        document.querySelectorAll('[data-document-row]').forEach(function (row) {
            row.addEventListener('click', function (event) {
                if (event.target.closest('select, a, button, form, option')) {
                    return;
                }

                const documentId = this.dataset.documentRow;
                const versionId = getSelectedVersionIdForDocument(documentId);

                if (documentId && versionId) {
                    updateDetailPanel(documentId, versionId);
                }
            });
        });

        const firstSelect = document.querySelector('.document-version-select');
        if (firstSelect && firstSelect.value) {
            updateDetailPanel(firstSelect.dataset.documentId, firstSelect.value);
        }

        document.querySelectorAll('.document-action-select').forEach(function (select) {
            select.addEventListener('change', function () {
                const action = this.value;

                if (action === 'add_version') {
                    window.location.href = this.dataset.createVersionUrl;
                    return;
                }

                if (action === 'delete') {
                    const ok = confirm('Supprimer ce document et toutes ses versions ?');

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

        document.querySelectorAll('.document-validation-select').forEach(function (select) {
            select.addEventListener('change', function () {
                const action = this.value;

                if (!action) {
                    return;
                }

                currentValidationForm = this.closest('form');
                currentValidationAction = action;
                currentValidationSelect = this;

                documentRefusCommentaire.value = '';
                documentRefusWrapper.classList.add('hidden');

                let label = 'cette validation';

                if (action === 'validation_technique') label = 'la validation technique';
                if (action === 'validation_administrative') label = 'la validation administrative';
                if (action === 'validation_financiere') label = 'la validation financière';
                if (action === 'refus') label = 'le refus';

                documentValidationTitle.textContent = 'Confirmer la validation';
                documentValidationText.textContent = 'Voulez-vous confirmer ' + label + ' ?';
                documentValidationConfirm.textContent = action === 'refus' ? 'Refuser' : 'Valider';

                if (action === 'refus') {
                    documentRefusWrapper.classList.remove('hidden');
                }

                documentValidationModal.classList.remove('hidden');
                documentValidationModal.classList.add('flex');
            });
        });

        documentValidationCancel.addEventListener('click', function () {
            closeDocumentValidationModal();
        });

        documentValidationModal.addEventListener('click', function (e) {
            if (e.target === documentValidationModal) {
                closeDocumentValidationModal();
            }
        });

        documentValidationConfirm.addEventListener('click', function () {
            if (!currentValidationForm || !currentValidationAction) {
                closeDocumentValidationModal();
                return;
            }

            if (currentValidationAction === 'refus' && !documentRefusCommentaire.value.trim()) {
                alert('Le commentaire du refus est obligatoire.');
                documentRefusCommentaire.focus();
                return;
            }

            currentValidationForm.querySelector('.js-document-validation-input').value = currentValidationAction;
            currentValidationForm.querySelector('.js-document-validation-comment-input').value = documentRefusCommentaire.value.trim();

            currentValidationForm.submit();
        });

        function closeDocumentValidationModal() {
            documentValidationModal.classList.add('hidden');
            documentValidationModal.classList.remove('flex');

            documentRefusCommentaire.value = '';

            if (currentValidationSelect) {
                currentValidationSelect.value = '';
            }

            currentValidationForm = null;
            currentValidationAction = null;
            currentValidationSelect = null;

            documentRefusWrapper.classList.add('hidden');
            documentValidationTitle.textContent = 'Confirmer la validation';
            documentValidationText.textContent = 'Voulez-vous confirmer cette action ?';
            documentValidationConfirm.textContent = 'Confirmer';
        }

        function buildValidationBadge(type) {
            let badgeClass = 'bg-gray-100 text-gray-800 ring-gray-200';

            switch (type) {
                case 'validation_technique':
                case 'validation_administrative':
                case 'validation_financiere':
                    badgeClass = 'bg-green-100 text-green-800 ring-green-200';
                    break;
                case 'refus':
                    badgeClass = 'bg-red-100 text-red-800 ring-red-200';
                    break;
            }

            return `
                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium ring-1 ring-inset ${badgeClass}">
                    ${formatValidationType(type)}
                </span>
            `;
        }

        function formatValidationType(type) {
            switch (type) {
                case 'validation_technique':
                    return 'Validation technique';
                case 'validation_administrative':
                    return 'Validation administrative';
                case 'validation_financiere':
                    return 'Validation financière';
                case 'refus':
                    return 'Refus';
                default:
                    return type;
            }
        }

        function renderValidationList(validations) {
            if (!Array.isArray(validations) || validations.length === 0) {
                return '';
            }

            return validations.map(validation => {
                const badge = buildValidationBadge(validation.type || '');
                const date = validation.date ? `le ${validation.date}` : '';
                const prenom = (validation.validateur_prenom || '').trim();
                const nom = (validation.validateur_nom || '').trim();
                const structure = (validation.validateur_structure || '').trim();

                const nomComplet = `${prenom} ${nom}`.trim();

                let auteur = '';

                if (nomComplet && structure) {
                    auteur = ` par ${escapeHtml(nomComplet)} (${escapeHtml(structure)})`;
                } else if (nomComplet) {
                    auteur = ` par ${escapeHtml(nomComplet)}`;
                }
                const commentaire = (validation.commentaire || '').trim();

                return `
                    <div class="space-y-2">
                        <div class="flex flex-wrap items-center gap-2 text-sm text-gray-700">
                            ${badge}
                            <span>${date}${auteur}</span>
                        </div>
                        ${commentaire !== ''
                            ? `<div class="rounded-lg bg-gray-50 px-3 py-2 text-sm text-gray-700">${escapeHtml(commentaire)}</div>`
                            : ''
                        }
                    </div>
                `;
            }).join('');
        }

        function escapeHtml(value) {
            const div = document.createElement('div');
            div.textContent = value || '';
            return div.innerHTML;
        }
    });
    </script>
</x-app-layout>