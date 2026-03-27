<?php

namespace App\Policies;

use App\Models\Projet;
use App\Models\User;

class ProjetPolicy
{
    /**
     * L'admin du site peut tout faire.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->estAdmin()) {
            return true;
        }

        return null;
    }

    /**
     * Tous les utilisateurs connectés peuvent voir la liste des projets.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Tous les utilisateurs connectés peuvent voir un projet.
     */
    public function view(User $user, Projet $projet): bool
    {
        if ($user->estAdmin()) {
            return true;
        }

        return $projet->participants()
            ->where('users.id', $user->id)
            ->where('projet_utilisateurs.actif', 1)
            ->exists();
    }

    /**
     * Pour l'instant : seuls les admins.
     */
    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Projet $projet): bool
    {
        if ($user->estAdmin()) {
            return true;
        }

        return $projet->participants()
            ->where('users.id', $user->id)
            ->where('projet_utilisateurs.actif', 1)
            ->exists();
    }

    public function delete(User $user, Projet $projet): bool
    {
        return $user->estAdmin();
    }
}