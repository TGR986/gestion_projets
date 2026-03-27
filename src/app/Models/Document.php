<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'type_validation',
        'commentaire_validation',
        'valide_par',
        'date_validation',
    ];

    public $timestamps = true;

    const CREATED_AT = 'date_creation';
    const UPDATED_AT = 'date_modification';

    public function projet(): BelongsTo
    {
        return $this->belongsTo(Projet::class, 'projet_id');
    }

    public function etape(): BelongsTo
    {
        return $this->belongsTo(ProjetEtape::class, 'projet_etape_id');
    }

    public function typeDocument(): BelongsTo
    {
        return $this->belongsTo(TypeDocument::class, 'type_document_id');
    }

    public function versions(): HasMany
    {
        return $this->hasMany(DocumentVersion::class, 'document_id')
            ->orderByDesc('numero_version');
    }

    public function versionCourante()
    {
        return $this->hasOne(DocumentVersion::class, 'document_id')
            ->where('est_version_courante', 1);
    }

    public function commentaires(): HasMany
    {
        return $this->hasMany(DocumentCommentaire::class, 'document_id')
            ->orderByDesc('date_commentaire');
    }

    public function validations(): HasMany
    {
        return $this->hasMany(DocumentValidation::class, 'document_id')
            ->orderByDesc('date_decision');
    }

    public function createur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cree_par');
    }
}