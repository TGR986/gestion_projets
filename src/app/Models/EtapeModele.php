<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EtapeModele extends Model
{
    protected $table = 'etapes_modele';

    protected $fillable = [
        'code',
        'libelle',
        'description',
        'ordre_affichage',
        'phase',
        'obligatoire',
    ];

    public $timestamps = false;

    public function projetEtapes()
    {
        return $this->hasMany(ProjetEtape::class, 'etape_modele_id');
    }

    public function getLibelleAfficheAttribute(): string
    {
        $libelleNormalise = Str::lower(trim(Str::ascii($this->libelle ?? '')));
        $phaseNormalisee  = Str::lower(trim(Str::ascii($this->phase ?? '')));

        if ($libelleNormalise === $phaseNormalisee) {
            return $this->libelle;
        }

        return $this->libelle . ' (' . $this->phase . ')';
    }
}