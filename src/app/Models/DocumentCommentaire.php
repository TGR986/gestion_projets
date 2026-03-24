<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class DocumentCommentaire extends Model
{
    protected $table = 'document_commentaires';

    protected $fillable = [
        'document_id',
        'document_version_id',
        'utilisateur_id',
        'type_commentaire',
        'commentaire',
        'est_resolu',
    ];

    public $timestamps = false;

    protected $casts = [
        'est_resolu' => 'boolean',
        'date_commentaire' => 'datetime',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id');
    }

    public function version()
    {
        return $this->belongsTo(DocumentVersion::class, 'document_version_id');
    }

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }
}