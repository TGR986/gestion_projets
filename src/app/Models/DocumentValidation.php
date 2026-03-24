<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class DocumentValidation extends Model
{
    protected $table = 'document_validations';

    protected $fillable = [
        'document_id',
        'document_version_id',
        'utilisateur_id',
        'decision',
        'commentaire',
    ];

    public $timestamps = false;

    protected $casts = [
        'date_decision' => 'datetime',
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