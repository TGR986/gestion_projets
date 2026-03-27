<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentVersionValidation extends Model
{
    protected $table = 'document_version_validations';

    protected $fillable = [
        'document_version_id',
        'type_validation',
        'commentaire',
        'valide_par',
        'date_validation',
    ];

    protected $casts = [
        'date_validation' => 'datetime',
    ];

    public function version(): BelongsTo
    {
        return $this->belongsTo(DocumentVersion::class, 'document_version_id');
    }

    public function validateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'valide_par');
    }
}