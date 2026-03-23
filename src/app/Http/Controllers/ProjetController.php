<?php

namespace App\Http\Controllers;

use App\Models\Projet;
use App\Models\User;
use App\Models\ProjetUtilisateur;
use App\Models\ProjetEtape;
use App\Models\EtapeModele;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProjetController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Projet::class);

        $projets = Projet::with('createur')->get();

        return view('projets.index', compact('projets'));
    }

    public function show(Projet $projet)
    {
        $this->authorize('view', $projet);

        $projet->load([
            'createur',
            'participants.utilisateur',
            'etapes' => function ($query) {
                $query->with([
                    'etapeModele',
                    'validateur',
                    'enfants.etapeModele',
                    'enfants.validateur',
                ])
                ->orderBy('ordre_reel')
                ->orderBy('id');
            },
        ]);

        return view('projets.show', compact('projet'));
    }

    public function create()
    {
        $this->authorize('create', Projet::class);

        return view('projets.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Projet::class);

        $validated = $request->validate([
            'code_projet' => 'required|string|max:50|unique:projets,code_projet',
            'intitule' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type_projet' => 'nullable|string|max:100',
            'date_debut_prevue' => 'nullable|date',
            'date_fin_prevue' => 'nullable|date|after_or_equal:date_debut_prevue',
        ]);

        $validated['statut_global'] = 'brouillon';
        $validated['cree_par'] = auth()->id();

        Projet::create($validated);

        return redirect()
            ->route('projets.index')
            ->with('success', 'Projet créé avec succès.');
    }

    public function edit(Projet $projet)
    {
        $this->authorize('update', $projet);

        return view('projets.edit', compact('projet'));
    }

    public function update(Request $request, Projet $projet)
    {
        $this->authorize('update', $projet);

        $validated = $request->validate([
            'code_projet' => 'required|string|max:50|unique:projets,code_projet,' . $projet->id,
            'intitule' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type_projet' => 'nullable|string|max:100',
            'statut_global' => 'required|in:brouillon,en_preparation,en_cours,suspendu,termine,archive',
            'date_debut_prevue' => 'nullable|date',
            'date_fin_prevue' => 'nullable|date|after_or_equal:date_debut_prevue',
            'date_fin_reelle' => 'nullable|date',
        ]);

        $projet->update($validated);

        return redirect()
            ->route('projets.index')
            ->with('success', 'Projet modifié avec succès.');
    }

    public function destroy(Projet $projet)
    {
        $this->authorize('delete', $projet);

        $projet->delete();

        return redirect()
            ->route('projets.index')
            ->with('success', 'Projet supprimé avec succès.');
    }

    public function createParticipant(Projet $projet)
    {
        $this->authorize('update', $projet);

        $utilisateurs = User::orderBy('name')->get();

        return view('projets.participants.create', compact('projet', 'utilisateurs'));
    }

    public function storeParticipant(Request $request, Projet $projet)
    {
        $this->authorize('update', $projet);

        $validated = $request->validate([
            'utilisateur_id' => [
                'required',
                'exists:users,id',
                Rule::unique('projet_utilisateurs', 'utilisateur_id')
                    ->where(fn ($query) => $query->where('projet_id', $projet->id)),
            ],
            'fonction_projet' => 'nullable|string|max:150',
            'est_chef_projet' => 'nullable|boolean',
        ]);

        ProjetUtilisateur::create([
            'projet_id' => $projet->id,
            'utilisateur_id' => $validated['utilisateur_id'],
            'fonction_projet' => $validated['fonction_projet'] ?? null,
            'est_chef_projet' => $request->has('est_chef_projet') ? 1 : 0,
            'actif' => 1,
            'date_affectation' => now(),
        ]);

        return redirect()
            ->route('projets.show', $projet)
            ->with('success', 'Participant ajouté avec succès.');
    }

    public function destroyParticipant(Projet $projet, ProjetUtilisateur $participant)
    {
        $this->authorize('update', $projet);

        if ($participant->projet_id !== $projet->id) {
            abort(404);
        }

        $participant->delete();

        return redirect()
            ->route('projets.show', $projet)
            ->with('success', 'Participant supprimé avec succès.');
    }

    public function createEtape(Projet $projet)
    {
        $this->authorize('update', $projet);

        $etapesModeles = EtapeModele::orderBy('ordre_affichage')->get();

        return view('projets.etapes.create', compact('projet', 'etapesModeles'));
    }

    public function storeEtape(Request $request, Projet $projet)
    {
        $this->authorize('update', $projet);

        $validated = $request->validate([
            'etape_modele_id' => 'required|exists:etapes_modele,id',
            'titre_personnalise' => 'nullable|string|max:255',
            'statut' => 'required|in:a_faire,en_cours,en_attente_validation,validee,refusee,bloquee,cloturee',
        ]);

        $ordreSuivant = ($projet->etapes()->max('ordre_reel') ?? 0) + 1;

        ProjetEtape::create([
            'projet_id' => $projet->id,
            'etape_modele_id' => $validated['etape_modele_id'],
            'titre_personnalise' => $validated['titre_personnalise'] ?? null,
            'ordre_reel' => $ordreSuivant,
            'statut' => $validated['statut'],
            'date_ouverture' => now(),
        ]);

        return redirect()
            ->route('projets.show', $projet)
            ->with('success', 'Étape ajoutée avec succès.');
    }

    public function startEtape(Projet $projet, ProjetEtape $etape)
    {
        $this->authorize('changerStatut', $etape);

        if ($etape->projet_id !== $projet->id) {
            abort(404);
        }

        $etape->update([
            'statut' => 'en_cours',
            'date_ouverture' => now(),
        ]);

        return back()->with('success', 'Étape démarrée.');
    }

    public function submitEtape(Projet $projet, ProjetEtape $etape)
    {
        $this->authorize('changerStatut', $etape);

        if ($etape->projet_id !== $projet->id) {
            abort(404);
        }

        $etape->update([
            'statut' => 'en_attente_validation',
        ]);

        return back()->with('success', 'Étape soumise à validation.');
    }

    public function validateEtape(Projet $projet, ProjetEtape $etape)
    {
        $this->authorize('changerStatut', $etape);

        if ($etape->projet_id !== $projet->id) {
            abort(404);
        }

        $etape->update([
            'statut' => 'validee',
            'date_cloture' => now(),
            'validee_par' => auth()->id(),
        ]);

        return back()->with('success', 'Étape validée.');
    }

    public function rejectEtape(Projet $projet, ProjetEtape $etape)
    {
        $this->authorize('changerStatut', $etape);

        if ($etape->projet_id !== $projet->id) {
            abort(404);
        }

        $etape->update([
            'statut' => 'refusee',
            'validee_par' => auth()->id(),
        ]);

        return back()->with('success', 'Étape refusée.');
    }

    public function actionEtape(Request $request, Projet $projet, ProjetEtape $etape)
    {
        $this->authorize('changerStatut', $etape);

        if ($etape->projet_id !== $projet->id) {
            abort(404);
        }

        $validated = $request->validate([
            'action' => 'required|in:demarrer,soumettre,valider,refuser',
        ]);

        $action = $validated['action'];

        $etapePrecedente = ProjetEtape::where('projet_id', $projet->id)
            ->where('ordre_reel', '<', $etape->ordre_reel)
            ->orderByDesc('ordre_reel')
            ->first();

        if ($action === 'demarrer') {
            if ($etapePrecedente && $etapePrecedente->statut !== 'validee') {
                return back()->withErrors([
                    'action' => 'Impossible de démarrer cette étape tant que l’étape précédente n’est pas validée.',
                ]);
            }

            $etape->update([
                'statut' => 'en_cours',
                'date_ouverture' => $etape->date_ouverture ?: now(),
            ]);

            return back()->with('success', 'Étape démarrée.');
        }

        if ($action === 'soumettre') {
            if ($etape->statut !== 'en_cours') {
                return back()->withErrors([
                    'action' => 'Action non autorisée.',
                ]);
            }

            $etape->update([
                'statut' => 'en_attente_validation',
            ]);

            return back()->with('success', 'Étape soumise à validation.');
        }

        if ($action === 'valider') {
            if ($etape->statut !== 'en_attente_validation') {
                return back()->withErrors([
                    'action' => 'Action non autorisée.',
                ]);
            }

            $etape->update([
                'statut' => 'validee',
                'date_cloture' => now(),
                'validee_par' => auth()->id(),
            ]);

            return back()->with('success', 'Étape validée.');
        }

        if ($action === 'refuser') {
            if ($etape->statut !== 'en_attente_validation') {
                return back()->withErrors([
                    'action' => 'Action non autorisée.',
                ]);
            }

            $etape->update([
                'statut' => 'refusee',
                'validee_par' => auth()->id(),
            ]);

            return back()->with('success', 'Étape refusée.');
        }

        return back()->withErrors([
            'action' => 'Action invalide.',
        ]);
    }

    public function editEtape(Projet $projet, ProjetEtape $etape)
    {
        $this->authorize('update', $etape);

        if ($etape->projet_id !== $projet->id) {
            abort(404);
        }

        $etapesModeles = EtapeModele::orderBy('ordre_affichage')->get();

        return view('projets.etapes.edit', compact('projet', 'etape', 'etapesModeles'));
    }

    public function updateEtape(Request $request, Projet $projet, ProjetEtape $etape)
    {
        $this->authorize('update', $etape);

        if ($etape->projet_id !== $projet->id) {
            abort(404);
        }

        $validated = $request->validate([
            'etape_modele_id' => 'required|exists:etapes_modele,id',
            'titre_personnalise' => 'nullable|string|max:255',
            'ordre_reel' => 'required|integer|min:1',
            'statut' => 'required|in:a_faire,en_cours,en_attente_validation,validee,refusee,bloquee,cloturee',
            'date_ouverture' => 'nullable|date',
            'date_cloture' => 'nullable|date',
        ]);

        $etape->update($validated);

        return redirect()
            ->route('projets.show', $projet)
            ->with('success', 'Étape modifiée avec succès.');
    }

    public function destroyEtape(Projet $projet, ProjetEtape $etape)
    {
        $this->authorize('delete', $etape);

        if ($etape->projet_id !== $projet->id) {
            abort(404);
        }

        $etape->delete();

        return redirect()
            ->route('projets.show', $projet)
            ->with('success', 'Étape supprimée avec succès.');
    }
}