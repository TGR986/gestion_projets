<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

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
    ];

    public $timestamps = true;

    const CREATED_AT = 'date_depot';
    const UPDATED_AT = null;

    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id');
    }

    public function deposant()
    {
        return $this->belongsTo(User::class, 'depose_par');
    }
}