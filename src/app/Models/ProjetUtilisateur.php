<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjetUtilisateur extends Model
{
    protected $table = 'projet_utilisateurs';

    public $timestamps = false;

    protected $fillable = [
        'projet_id',
        'utilisateur_id',
        'fonction_projet',
        'est_chef_projet',
        'actif',
        'date_affectation',
    ];

    const CREATED_AT = null;
    const UPDATED_AT = null;

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }

    public function projet()
    {
        return $this->belongsTo(Projet::class, 'projet_id');
    }
}