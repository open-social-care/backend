<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $currentUser): bool
    {
        return $currentUser->isAdminSystem();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $currentUser): bool
    {
        return $currentUser->isAdminSystem();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function viewByOrganization(User $currentUser, Organization $organization): bool
    {
        return $currentUser->isAdminSystem() || $currentUser->canAccessAnotherUserByOrganization($organization);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function viewByUserOrganizations(User $currentUser, User $user): bool
    {
        return $currentUser->isAdminSystem() || $currentUser->canAccessUser($user);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $currentUser): bool
    {
        return $currentUser->isAdminSystem();
    }

    /**
     * Determine whether the user can create models.
     */
    public function createByOrganization(User $currentUser, Organization $organization): bool
    {
        return $currentUser->isAdminSystem() || $currentUser->canAccessAnotherUserByOrganization($organization);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $currentUser, User $user): bool
    {
        return $currentUser->isAdminSystem() || $currentUser->canAccessUser($user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $currentUser): bool
    {
        return $currentUser->isAdminSystem();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function disassociateUserFromOrganization(User $currentUser, User $user): bool
    {
        return $currentUser->isAdminSystem() || $currentUser->canAccessUser($user);
    }
}
