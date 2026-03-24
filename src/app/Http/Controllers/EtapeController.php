<?php

namespace App\Http\Controllers;

use App\Models\Projet;
use App\Models\ProjetEtape;
use App\Models\EtapeModele;
use Illuminate\Http\Request;
use App\Models\EtapeCommentaire;
use Illuminate\Support\Facades\Auth;

class EtapeController extends Controller
{
    public function create(Request $request, Projet $projet)
    {
        $parentId = $request->query('parent_id');

        if ($parentId) {
            $etapeParent = ProjetEtape::findOrFail($parentId);

            $etapesModeles = EtapeModele::where('type_niveau', 'sous-étape')
                ->where('phase', $etapeParent->etapeModele->phase)
                ->orderBy('ordre_affichage')
                ->get();
        } else {
            $etapesModeles = EtapeModele::where('type_niveau', 'étape')
                ->orderBy('ordre_affichage')
                ->get();
        }

        return view('projets.etapes.create', compact(
            'projet',
            'etapesModeles',
            'parentId'
        ));
    }

    public function store(Request $request, Projet $projet)
    {
        $this->authorize('update', $projet);

        $data = $request->validate([
            'etape_modele_id' => ['nullable', 'exists:etapes_modele,id'],
            'titre_personnalise' => ['nullable', 'string', 'max:255'],
            'statut' => ['required', 'in:a_faire,en_cours,en_attente_validation,validee,refusee,bloquee,cloturee'],
            'parent_id' => ['nullable', 'exists:projet_etapes,id'],
        ]);

        if (!empty($data['parent_id'])) {
            $parent = ProjetEtape::where('projet_id', $projet->id)
                ->findOrFail($data['parent_id']);

            $ordreReel = (int) ProjetEtape::where('projet_id', $projet->id)
                ->where('parent_id', $parent->id)
                ->max('ordre_reel') + 1;
        } else {
            $ordreReel = (int) ProjetEtape::where('projet_id', $projet->id)
                ->whereNull('parent_id')
                ->max('ordre_reel') + 1;
        }

        ProjetEtape::create([
            'projet_id' => $projet->id,
            'parent_id' => $data['parent_id'] ?? null,
            'etape_modele_id' => $data['etape_modele_id'] ?? null,
            'titre_personnalise' => $data['titre_personnalise'] ?? null,
            'statut' => $data['statut'],
            'ordre_reel' => $ordreReel,
        ]);

        if (!empty($data['parent_id'])) {
            return redirect()
                ->route('projets.etapes.show', [$projet, $data['parent_id']])
                ->with('success', 'Sous-étape ajoutée avec succès.');
        }

        return redirect()
            ->route('projets.show', $projet)
            ->with('success', 'Étape ajoutée avec succès.');
    }

    public function edit(Projet $projet, ProjetEtape $etape)
    {
        $this->authorize('update', $etape);

        $etapesModeles = EtapeModele::orderBy('ordre_affichage')->get();

        return view('projets.etapes.edit', compact('projet', 'etape', 'etapesModeles'));
    }

    public function update(Request $request, Projet $projet, ProjetEtape $etape)
    {
        $this->authorize('update', $etape);

        $data = $request->validate([
            'etape_modele_id' => ['nullable', 'exists:etapes_modele,id'],
            'titre_personnalise' => ['nullable', 'string', 'max:255'],
            'ordre_reel' => ['required', 'integer', 'min:1'],
            'statut' => ['required', 'in:a_faire,en_cours,en_attente_validation,validee,refusee,bloquee,cloturee'],
        ]);

        $etape->update($data);

        return redirect()
            ->route('projets.show', $projet)
            ->with('success', 'Étape modifiée avec succès.');
    }

    public function destroy(Projet $projet, ProjetEtape $etape)
    {
        $this->authorize('delete', $etape);

        $etape->delete();

        return redirect()
            ->route('projets.show', $projet)
            ->with('success', 'Étape supprimée.');
    }

    public function changerStatut(Request $request, Projet $projet, ProjetEtape $etape)
    {
        $this->authorize('changerStatut', $etape);

        $data = $request->validate([
            'action' => ['required', 'in:demarrer,soumettre,valider,refuser'],
        ]);

        switch ($data['action']) {
            case 'demarrer':
                if ($etape->statut === 'a_faire') {
                    $etape->statut = 'en_cours';
                }
                break;

            case 'soumettre':
                if ($etape->statut === 'en_cours') {
                    $etape->statut = 'en_attente_validation';
                }
                break;

            case 'valider':
                if ($etape->statut === 'en_attente_validation') {
                    $etape->statut = 'validee';
                }
                break;

            case 'refuser':
                if ($etape->statut === 'en_attente_validation') {
                    $etape->statut = 'en_cours';
                }
                break;
        }

        $etape->save();

        return redirect()
            ->route('projets.show', $projet)
            ->with('success', 'Statut mis à jour.');
    }

    public function show(Projet $projet, ProjetEtape $etape)
    {
        $this->authorize('view', $projet);


        // sécurité : vérifier que l’étape appartient bien au projet
        abort_unless($etape->projet_id === $projet->id, 404);


        // charger les relations utiles
        $etape->load([
            'etapeModele',
            'validateur',
            'enfants.etapeModele',
            'enfants.validateur',

            // 🔽 DOCUMENTS
            'documents.versionCourante',
            'documents.commentaires',
            'documents.versions.deposant',

            'commentaires.auteur',
        ]);


        return view('projets.etapes.show', compact('projet', 'etape'));
    }

    public function storeCommentaire(Request $request, $projetId, $etapeId)
    {
        $projet = Projet::findOrFail($projetId);
        $etape = ProjetEtape::findOrFail($etapeId);

        abort_unless($etape->projet_id === $projet->id, 404);

        $data = $request->validate([
            'contenu' => ['required', 'string', 'max:5000'],
        ]);

        EtapeCommentaire::create([
            'projet_etape_id' => $etape->id,
            'user_id' => Auth::id(),
            'contenu' => $data['contenu'],
        ]);

        return redirect()
            ->route('projets.etapes.show', [$projet, $etape])
            ->with('success', 'Commentaire ajouté avec succès.');
    }

    private function peutModifierCommentaire(\App\Models\EtapeCommentaire $commentaire): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        return $user->id === $commentaire->user_id;
    }

    private function peutSupprimerCommentaire(\App\Models\EtapeCommentaire $commentaire): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        return $user->id === $commentaire->user_id || $user->estAdmin();
    }

    public function updateCommentaire(Request $request, $projetId, $etapeId, $commentaireId)
    {
        $projet = Projet::findOrFail($projetId);
        $etape = ProjetEtape::findOrFail($etapeId);
        $commentaire = \App\Models\EtapeCommentaire::findOrFail($commentaireId);

        abort_unless($etape->projet_id === $projet->id, 404);
        abort_unless($commentaire->projet_etape_id === $etape->id, 404);
        abort_unless($this->peutModifierCommentaire($commentaire), 403);

        $data = $request->validate([
            'contenu' => ['required', 'string', 'max:5000'],
        ]);

        $commentaire->update([
            'contenu' => $data['contenu'],
        ]);

        return redirect()
            ->route('projets.etapes.show', [$projet, $etape])
            ->with('success', 'Commentaire modifié avec succès.');
    }

    public function destroyCommentaire($projetId, $etapeId, $commentaireId)
    {
        $projet = Projet::findOrFail($projetId);
        $etape = ProjetEtape::findOrFail($etapeId);
        $commentaire = \App\Models\EtapeCommentaire::findOrFail($commentaireId);

        abort_unless($etape->projet_id === $projet->id, 404);
        abort_unless($commentaire->projet_etape_id === $etape->id, 404);
        abort_unless($this->peutSupprimerCommentaire($commentaire), 403);

        $commentaire->delete();

        return redirect()
            ->route('projets.etapes.show', [$projet, $etape])
            ->with('success', 'Commentaire supprimé avec succès.');
    }
}