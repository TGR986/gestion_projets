<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjetEtape extends Model
{
    protected $table = 'projet_etapes';

    protected $fillable = [
        'projet_id',
        'parent_id',
        'etape_modele_id',
        'titre_personnalise',
        'ordre_reel',
        'statut',
        'date_ouverture',
        'date_cloture',
        'validee_par',
        'motif_refus',
    ];

    const CREATED_AT = null;
    const UPDATED_AT = null;

    public function projet()
    {
        return $this->belongsTo(Projet::class, 'projet_id');
    }

    public function etapeModele()
    {
        return $this->belongsTo(EtapeModele::class, 'etape_modele_id');
    }

    public function validateur()
    {
        return $this->belongsTo(User::class, 'validee_par');
    }

    public function parent()
    {
        return $this->belongsTo(ProjetEtape::class, 'parent_id');
    }

    public function enfants()
    {
        return $this->hasMany(ProjetEtape::class, 'parent_id')
            ->orderBy('ordre_reel')
            ->orderBy('id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'projet_etape_id')
            ->orderByDesc('date_creation');
    }

    public function commentaires()
    {
        return $this->hasMany(EtapeCommentaire::class, 'projet_etape_id')
            ->latest();
    }
}