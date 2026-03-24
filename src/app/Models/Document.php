<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Document extends Model
{
    protected $table = 'documents';

    protected $fillable = [
        'projet_id',
        'projet_etape_id',
        'type_document_id',
        'titre',
        'description',
        'statut_document',
        'cree_par',
    ];

    public $timestamps = true;

    const CREATED_AT = 'date_creation';
    const UPDATED_AT = 'date_modification';

    public function projet()
    {
        return $this->belongsTo(Projet::class, 'projet_id');
    }

    public function etape()
    {
        return $this->belongsTo(ProjetEtape::class, 'projet_etape_id');
    }

    public function versions()
    {
        return $this->hasMany(DocumentVersion::class, 'document_id')
            ->orderByDesc('numero_version');
    }

    public function versionCourante()
    {
        return $this->hasOne(DocumentVersion::class, 'document_id')
            ->where('est_version_courante', 1);
    }

    public function commentaires()
    {
        return $this->hasMany(DocumentCommentaire::class, 'document_id')
            ->orderByDesc('date_commentaire');
    }

    public function validations()
    {
        return $this->hasMany(DocumentValidation::class, 'document_id')
            ->orderByDesc('date_decision');
    }

    public function createur()
    {
        return $this->belongsTo(User::class, 'cree_par');
    }
}