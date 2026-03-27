<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoleValidationPermission extends Model
{
    protected $table = 'role_validation_permissions';

    protected $fillable = [
        'role_id',
        'validation_type',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}