<?php

namespace App\Policies;

use App\Models\FormTemplate;
use App\Models\User;

class FormTemplatePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdminSystem();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
        return $user->isAdminSystem() || $user->isManager();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdminSystem() || $user->isManager();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, FormTemplate $formTemplate): bool
    {
        return $user->isAdminSystem() || $user->isManager();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FormTemplate $formTemplate): bool
    {
        return $user->isAdminSystem() || $user->isManager();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, FormTemplate $formTemplate): bool
    {
        return $user->isAdminSystem() || $user->isManager();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, FormTemplate $formTemplate): bool
    {
        return $user->isAdminSystem() || $user->isManager();
    }
}
