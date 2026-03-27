<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Role;
use App\Models\Service;
use App\Models\TypeDocument;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'prenom',
        'fonction',
        'structure',
        'email',
        'password',
        'is_admin',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    public function estAdmin(): bool
    {
        if ((bool) $this->is_admin) {
            return true;
        }

        return $this->hasRole('administrateur')
            || $this->hasRole('Administrateur');
    }

    public function isAdmin(): bool
    {
        return $this->estAdmin();
    }

    public function hasRole(string $value): bool
    {
        if ($this->relationLoaded('roles')) {
            return $this->roles->contains(function ($role) use ($value) {
                return $role->code === $value
                    || $role->libelle === $value;
            });
        }

        return $this->roles()
            ->where(function ($query) use ($value) {
                $query->where('code', $value)
                    ->orWhere('libelle', $value);
            })
            ->exists();
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            'utilisateur_roles',
            'utilisateur_id', // clé dans la table pivot
            'role_id'         // clé vers roles
        );
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(
            Service::class,
            'utilisateur_services',
            'utilisateur_id',
            'service_id'
        );
    }

    public function canDownloadDocument(int $typeDocumentId): bool
    {
        return TypeDocument::allowedForDownload($this)
            ->where('id', $typeDocumentId)
            ->exists();
    }

    public function hasValidationPermission(string $validationType): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        return $this->roles()
            ->whereHas('validationPermissions', function ($query) use ($validationType) {
                $query->where('validation_type', $validationType);
            })
            ->exists();
    }

    public function canViewDocument(int $typeDocumentId): bool
    {
        return TypeDocument::allowedForView($this)
            ->where('id', $typeDocumentId)
            ->exists();
    }

    public function canUploadTypeDocument(int $typeDocumentId): bool
    {
        return TypeDocument::allowedForUpload($this)
            ->where('id', $typeDocumentId)
            ->exists();
    }

    public function isActive(): bool
    {
        return (bool) $this->is_active;
    }
}