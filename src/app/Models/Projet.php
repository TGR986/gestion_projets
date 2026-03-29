<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\ProjetUtilisateur;
use App\Models\ProjetEtape;
use App\Models\EtapeCommentaire;

class Projet extends Model
{
    protected $table = 'projets';

    protected $fillable = [
        'code_projet',
        'intitule',
        'description',
        'type_projet',
        'statut_global',
        'budget_previsionnel',
        'date_debut_prevue',
        'date_fin_prevue',
        'date_fin_reelle',
        'cree_par',
    ];

    const CREATED_AT = 'date_creation';
    const UPDATED_AT = 'date_modification';

    public function createur()
    {
        return $this->belongsTo(User::class, 'cree_par');
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'projet_utilisateurs', 'projet_id', 'utilisateur_id')
            ->withPivot(['id', 'fonction_projet', 'est_chef_projet', 'actif', 'date_affectation']);
    }

    public function etapes()
    {
        return $this->hasMany(ProjetEtape::class, 'projet_id')->orderBy('ordre_reel');
    }

    public function commentaires()
    {
        return $this->hasMany(EtapeCommentaire::class, 'projet_id')->latest();
    }
}