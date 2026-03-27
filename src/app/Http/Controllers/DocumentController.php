<?php

namespace App\Http\Controllers;

use App\Models\Projet;
use App\Models\ProjetEtape;
use App\Models\Document;
use App\Models\DocumentVersion;
use App\Models\TypeDocument;
use App\Models\DocumentVersionValidation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DocumentController extends Controller
{
    public function download($id)
    {
        $version = DocumentVersion::with('document')->findOrFail($id);

        $document = $version->document;
        abort_unless($document, 404);

        $this->authorize('download', $document);

        $relativePath = ltrim((string) $version->chemin_fichier, '/');

        $candidats = [
            storage_path('app/private/' . $relativePath),
            storage_path('app/' . $relativePath),
        ];

        if (str_starts_with($relativePath, 'private/')) {
            $sansPrivate = preg_replace('#^private/#', '', $relativePath);
            $candidats[] = storage_path('app/private/' . $sansPrivate);
            $candidats[] = storage_path('app/' . $sansPrivate);
        }

        $fullPath = null;

        foreach ($candidats as $path) {
            if (file_exists($path)) {
                $fullPath = $path;
                break;
            }
        }

        if (!$fullPath) {
            abort(404, 'Fichier introuvable.');
        }

        return response()->download(
            $fullPath,
            $version->nom_fichier_original
        );
    }

    public function create($projetId, $etapeId)
    {
        $projet = Projet::findOrFail($projetId);
        $etape = ProjetEtape::with(['parent', 'etapeModele'])->findOrFail($etapeId);

        abort_unless($etape->projet_id === $projet->id, 404);

        $this->authorize('createForEtape', Document::class);

        $user = auth()->user();

        $typesDocuments = TypeDocument::allowedForUpload($user)->get();

        if (!$user->isAdmin() && $typesDocuments->isEmpty()) {
            abort(403, 'Vous n\'avez pas le droit d\'ajouter un document.');
        }

        return view('documents.create', compact('projet', 'etape', 'typesDocuments'));
    }

    public function store(Request $request, $projetId, $etapeId)
    {
        $projet = Projet::findOrFail($projetId);
        $etape = ProjetEtape::with('parent')->findOrFail($etapeId);

        abort_unless($etape->projet_id === $projet->id, 404);

        $data = $request->validate([
            'titre' => ['required', 'string', 'max:255'],
            'type_document_id' => ['required', 'exists:types_documents,id'],
            'fichier' => ['required', 'file', 'max:10240'],
        ]);

        $user = Auth::user();

        abort_unless($user instanceof User, 403);

        abort_unless(
            $user->canUploadTypeDocument((int) $data['type_document_id']),
            403
        );

        $fichier = $request->file('fichier');

        $nomOriginal = $fichier->getClientOriginalName();
        $extension = $fichier->getClientOriginalExtension();
        $mimeType = $fichier->getClientMimeType();
        $taille = $fichier->getSize();

        $nomStocke = (string) Str::uuid() . ($extension ? '.' . $extension : '');

        // stockage dans storage/app/private/documents
        $cheminStocke = $fichier->storeAs('documents', $nomStocke);

        $document = Document::create([
            'projet_id' => $projet->id,
            'projet_etape_id' => $etape->id,
            'type_document_id' => $data['type_document_id'],
            'titre' => $data['titre'],
            'description' => null,
            'statut_document' => 'brouillon',
            'cree_par' => Auth::id(),
        ]);

        DocumentVersion::create([
            'document_id' => $document->id,
            'numero_version' => 1,
            'nom_fichier_original' => $nomOriginal,
            'nom_fichier_stocke' => $nomStocke,
            'chemin_fichier' => $cheminStocke,
            'extension' => $extension ?: null,
            'mime_type' => $mimeType,
            'taille_octets' => $taille,
            'hash_fichier' => hash_file('sha256', $fichier->getRealPath()),
            'depose_par' => Auth::id(),
            'commentaire_version' => 'Version initiale',
            'est_version_courante' => 1,
        ]);

        return redirect()
            ->route('projets.etapes.show', [$projet->id, $etape->id])
            ->with('success', 'Document ajouté avec succès.');
    }

    public function createVersion($documentId)
    {
        $document = Document::with(['projet', 'etape', 'versions'])->findOrFail($documentId);

        $versionCourante = $document->versions()
            ->where('est_version_courante', 1)
            ->orderByDesc('numero_version')
            ->first();

        return view('documents.versions.create', compact('document', 'versionCourante'));
    }

    public function storeVersion(Request $request, $documentId)
    {
        $document = Document::with(['versions', 'etape', 'projet'])->findOrFail($documentId);

        $data = $request->validate([
            'fichier' => ['required', 'file', 'max:10240'],
            'commentaire_version' => ['nullable', 'string', 'max:1000'],
        ]);

        $fichier = $request->file('fichier');

        $nomOriginal = $fichier->getClientOriginalName();
        $extension = $fichier->getClientOriginalExtension();
        $mimeType = $fichier->getClientMimeType();
        $taille = $fichier->getSize();

        $nomStocke = (string) Str::uuid() . ($extension ? '.' . $extension : '');
        $cheminStocke = $fichier->storeAs('documents', $nomStocke);

        DB::transaction(function () use (
            $document,
            $data,
            $fichier,
            $nomOriginal,
            $extension,
            $mimeType,
            $taille,
            $nomStocke,
            $cheminStocke
        ) {
            $derniereVersion = $document->versions()
                ->lockForUpdate()
                ->orderByDesc('numero_version')
                ->first();

            $nouveauNumero = $derniereVersion
                ? $derniereVersion->numero_version + 1
                : 1;

            $document->versions()
                ->where('est_version_courante', 1)
                ->update(['est_version_courante' => 0]);

            DocumentVersion::create([
                'document_id' => $document->id,
                'numero_version' => $nouveauNumero,
                'nom_fichier_original' => $nomOriginal,
                'nom_fichier_stocke' => $nomStocke,
                'chemin_fichier' => $cheminStocke,
                'extension' => $extension ?: null,
                'mime_type' => $mimeType,
                'taille_octets' => $taille,
                'hash_fichier' => hash_file('sha256', $fichier->getRealPath()),
                'depose_par' => Auth::id(),
                'commentaire_version' => $data['commentaire_version'] ?? null,
                'est_version_courante' => 1,
            ]);
        });

        return redirect()
            ->route('projets.etapes.show', [$document->projet_id, $document->projet_etape_id])
            ->with('success', 'Nouvelle version ajoutée avec succès.');
    }

    public function destroy($documentId)
    {
        $document = Document::with(['versions', 'etape'])->findOrFail($documentId);

        abort_unless(Auth::check() && Auth::user()->estAdmin(), 403);

        $projetId = $document->projet_id;
        $etapeId = $document->projet_etape_id;

        foreach ($document->versions as $version) {
            $relativePath = ltrim((string) $version->chemin_fichier, '/');

            $candidats = [
                storage_path('app/private/' . $relativePath),
                storage_path('app/' . $relativePath),
            ];

            if (str_starts_with($relativePath, 'private/')) {
                $sansPrivate = preg_replace('#^private/#', '', $relativePath);
                $candidats[] = storage_path('app/private/' . $sansPrivate);
                $candidats[] = storage_path('app/' . $sansPrivate);
            }

            foreach ($candidats as $path) {
                if (file_exists($path)) {
                    @unlink($path);
                    break;
                }
            }
        }

        $document->versions()->delete();
        $document->delete();

        return redirect()
            ->route('projets.etapes.show', [$projetId, $etapeId])
            ->with('success', 'Document supprimé avec succès.');
    }

    public function validerVersion(Request $request, DocumentVersion $version)
    {
        abort_unless(Auth::check(), 403);

        $user = Auth::user();

        abort_unless($user instanceof User, 403);

        $validated = $request->validate([
            'validation_action' => ['required', 'in:validation_technique,validation_administrative,validation_financiere,refus'],
            'commentaire_validation' => ['nullable', 'string', 'max:3000'],
        ]);

        $validationAction = $validated['validation_action'];

        if (
            $validationAction === 'refus'
            && blank($validated['commentaire_validation'] ?? null)
        ) {
            return back()
                ->withErrors([
                    'commentaire_validation' => 'Le commentaire est obligatoire en cas de refus.',
                ])
                ->withInput();
        }

        // Sécurité métier :
        // - admin : tout autorisé
        // - non-admin : seulement les validations explicitement permises
        if (! $user->isAdmin()) {
            if ($validationAction === 'refus') {
                abort(403, 'Vous n\'êtes pas autorisé à refuser cette version.');
            }

            abort_unless(
                $user->hasValidationPermission($validationAction),
                403,
                'Vous n\'êtes pas autorisé à effectuer cette validation.'
            );
        }

        DocumentVersionValidation::updateOrCreate(
            [
                'document_version_id' => $version->id,
                'type_validation' => $validationAction,
            ],
            [
                'commentaire' => $validationAction === 'refus'
                    ? $validated['commentaire_validation']
                    : null,
                'valide_par' => Auth::id(),
                'date_validation' => now(),
            ]
        );

        return back()->with('success', 'Validation enregistrée pour cette version.');
    }
}