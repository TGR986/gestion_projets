<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentVersion extends Model
{
    protected $table = 'document_versions';

    protected $fillable = [
        'document_id',
        'numero_version',
        'nom_fichier_original',
        'nom_fichier_stocke',
        'chemin_fichier',
        'extension',
        'mime_type',
        'taille_octets',
        'hash_fichier',
        'depose_par',
        'commentaire_version',
        'est_version_courante',
        'type_validation',
        'commentaire_validation',
        'valide_par',
        'date_validation',
    ];

    public $timestamps = true;

    const CREATED_AT = 'date_depot';
    const UPDATED_AT = null;

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'document_id');
    }

    public function deposant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'depose_par');
    }

    public function validateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'valide_par');
    }

    public function validations(): HasMany
    {
        return $this->hasMany(DocumentVersionValidation::class, 'document_version_id')
            ->orderBy('date_validation')
            ->orderBy('id');
    }
}