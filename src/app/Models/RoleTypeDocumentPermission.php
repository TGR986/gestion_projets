<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoleTypeDocumentPermission extends Model
{
    protected $table = 'role_type_document_permissions';

    protected $fillable = [
        'role_id',
        'type_document_id',
        'can_view',
        'can_upload',
        'can_download',
    ];

    protected $casts = [
        'can_view' => 'boolean',
        'can_upload' => 'boolean',
        'can_download' => 'boolean',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function typeDocument(): BelongsTo
    {
        return $this->belongsTo(TypeDocument::class, 'type_document_id');
    }
}