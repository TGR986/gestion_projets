<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\ProjetUtilisateur;
use App\Models\ProjetEtape;

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
        return $this->hasMany(ProjetUtilisateur::class, 'projet_id');
    }

    public function etapes()
    {
        return $this->hasMany(ProjetEtape::class, 'projet_id')->orderBy('ordre_reel');
    }
}