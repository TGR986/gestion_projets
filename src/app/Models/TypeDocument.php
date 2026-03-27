<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Role;
use App\Models\User;

class TypeDocument extends Model
{
    protected $table = 'types_documents';

    protected $fillable = [
        'code',
        'libelle',
        'description',
        'domaine',
        'actif',
    ];

    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'role_type_document_permissions',
            'type_document_id',
            'role_id'
        )->withPivot(['can_view', 'can_upload', 'can_download']);
    }

    public function scopeAllowedForUpload(Builder $query, User $user): Builder
    {
        if ($user->isAdmin()) {
            return $query
                ->where('actif', 1)
                ->orderBy('libelle');
        }

        return $query
            ->where('actif', 1)
            ->whereHas('roles', function ($q) use ($user) {
                $q->whereIn('roles.id', $user->roles->pluck('id'))
                    ->where('role_type_document_permissions.can_upload', 1);
            })
            ->orderBy('libelle');
    }

    public function scopeAllowedForView(Builder $query, User $user): Builder
{
    if ($user->isAdmin()) {
        return $query
            ->where('actif', 1)
            ->orderBy('libelle');
    }

    return $query
        ->where('actif', 1)
        ->whereHas('roles', function ($q) use ($user) {
            $q->whereIn('roles.id', $user->roles->pluck('id'))
                ->where('role_type_document_permissions.can_view', 1);
        })
        ->orderBy('libelle');
    }

    public function scopeAllowedForDownload(Builder $query, User $user): Builder
    {
        if ($user->isAdmin()) {
            return $query
                ->where('actif', 1)
                ->orderBy('libelle');
        }

        return $query
            ->where('actif', 1)
            ->whereHas('roles', function ($q) use ($user) {
                $q->whereIn('roles.id', $user->roles->pluck('id'))
                    ->where('role_type_document_permissions.can_download', 1);
            })
            ->orderBy('libelle');
    }

    public static function userCanView(User $user, int $typeDocumentId): bool
    {
        return static::allowedForView($user)
            ->where('id', $typeDocumentId)
            ->exists();
    }

    public static function userCanDownload(User $user, int $typeDocumentId): bool
    {
        return static::allowedForDownload($user)
            ->where('id', $typeDocumentId)
            ->exists();
    }
}