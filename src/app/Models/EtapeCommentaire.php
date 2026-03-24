<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EtapeCommentaire extends Model
{
    protected $table = 'etape_commentaires';

    protected $fillable = [
        'projet_etape_id',
        'user_id',
        'contenu',
    ];

    public function etape()
    {
        return $this->belongsTo(ProjetEtape::class, 'projet_etape_id');
    }

    public function auteur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}