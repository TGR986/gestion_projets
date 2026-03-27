<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;

class DocumentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Document $document): bool
    {
        return \App\Models\TypeDocument::userCanView($user, $document->type_document_id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Document $document): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Document $document): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Document $document): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Document $document): bool
    {
        return $user->isAdmin();
    }

    public function createForEtape(User $user): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->roles()
            ->whereHas('typeDocumentPermissions', function ($query) {
                $query->where('can_upload', 1);
            })
            ->exists();
    }

    public function download(User $user, Document $document): bool
    {
        return \App\Models\TypeDocument::userCanDownload($user, $document->type_document_id);
    }
}
