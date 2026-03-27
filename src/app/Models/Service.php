<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $table = 'roles';

    protected $fillable = [
        'nom',
        'code',
        'description',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'utilisateur_services',
            'service_id',
            'utilisateur_id'
        );
    }

    public function typeDocumentPermissions(): HasMany
    {
        return $this->hasMany(RoleTypeDocumentPermission::class, 'role_id');
    }

    public function validationPermissions(): HasMany
    {
        return $this->hasMany(RoleValidationPermission::class, 'role_id');
    }
}