<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EtapeCommentaire extends Model
{
    protected $table = 'etape_commentaires';

    protected $fillable = [
        'projet_id',
        'projet_etape_id',
        'user_id',
        'contenu',
    ];

    public function projet(): BelongsTo
    {
        return $this->belongsTo(Projet::class, 'projet_id');
    }

    public function etape(): BelongsTo
    {
        return $this->belongsTo(ProjetEtape::class, 'projet_etape_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function auteur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}