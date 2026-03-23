<?php

namespace App\Policies;

use App\Models\ProjetEtape;
use App\Models\User;

class EtapePolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->estAdmin()) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, ProjetEtape $etape): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, ProjetEtape $etape): bool
    {
        return false;
    }

    public function delete(User $user, ProjetEtape $etape): bool
    {
        return false;
    }

    public function changerStatut(User $user, ProjetEtape $etape): bool
    {
        return false;
    }
}