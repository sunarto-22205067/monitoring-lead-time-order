<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Document;
use App\Models\User;

class DocumentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Document $document): bool
    {
        if ($user->hasRole('Admin')) {
            return true;
        }

        if ($user->id === $document->user_id) {
            return true;
        }

        if ($document->team_id && $user->teams()->where('teams.id', $document->team_id)->exists()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Document $document): bool
    {
        if ($user->hasRole('Admin')) {
            return true;
        }

        if ($user->id === $document->user_id) {
            return true;
        }

        // Manager of the team can also update?
        if ($document->team_id) {
            return $user->ownedTeams()->where('id', $document->team_id)->exists() ||
                   \App\Models\TeamMember::where('team_id', $document->team_id)
                       ->where('user_id', $user->id)
                       ->where('role', 'manager')
                       ->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Document $document): bool
    {
        if ($user->hasRole('Admin')) {
            return true;
        }

        if ($user->id === $document->user_id) {
            return true;
        }

        if ($document->team_id && $user->ownedTeams()->where('id', $document->team_id)->exists()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can download the document.
     */
    public function download(User $user, Document $document): bool
    {
        return $this->view($user, $document);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Document $document): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Document $document): bool
    {
        return false;
    }
}
