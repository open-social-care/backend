<?php

namespace App\Policies;

use App\Enums\RolesEnum;
use App\Models\Organization;
use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRoleByName(RolesEnum::ADMIN->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $currentUser): bool
    {
        if ($currentUser->hasRoleByName(RolesEnum::ADMIN->value) && $currentUser->organizations->isEmpty()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function viewByOrganization(User $currentUser, Organization $organization): bool
    {
        if ($currentUser->hasRoleByName(RolesEnum::ADMIN->value) && $currentUser->organizations->isEmpty()) {
            return true;
        }

        return $this->currentUserCanAccessUserByOrganization($currentUser, $organization);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function viewByUserOrganizations(User $currentUser, User $user): bool
    {
        if ($currentUser->hasRoleByName(RolesEnum::ADMIN->value) && $currentUser->organizations->isEmpty()) {
            return true;
        }

        return $this->currentUserCanAccessUser($currentUser, $user);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $currentUser): bool
    {
        if ($currentUser->hasRoleByName(RolesEnum::ADMIN->value) && $currentUser->organizations->isEmpty()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function createByOrganization(User $currentUser, Organization $organization): bool
    {
        if ($currentUser->hasRoleByName(RolesEnum::ADMIN->value) && $currentUser->organizations->isEmpty()) {
            return true;
        }

        return $this->currentUserCanAccessUserByOrganization($currentUser, $organization);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $currentUser, User $user): bool
    {
        if ($currentUser->hasRoleByName(RolesEnum::ADMIN->value) && $currentUser->organizations->isEmpty()) {
            return true;
        }

        return $this->currentUserCanAccessUser($currentUser, $user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $currentUser, User $user): bool
    {
        if ($currentUser->hasRoleByName(RolesEnum::ADMIN->value) && $currentUser->organizations->isEmpty()) {
            return true;
        }

        return $this->currentUserCanAccessUser($currentUser, $user);
    }

    private function currentUserCanAccessUserByOrganization(User $currentUser, Organization $organization): bool
    {
        $currentUserHasOrganizationUser = $currentUser
            ->organizations()
            ->where('organization_id', $organization->id)
            ->exists();

        return $currentUserHasOrganizationUser;
    }

    private function currentUserCanAccessUser(User $currentUser, User $user): bool
    {
        $userOrganizationsIds = $user->organizations()->pluck('organization_id');
        $currentUserHasOrganizationUser = $currentUser
            ->organizations()
            ->whereIn('organization_id', $userOrganizationsIds)
            ->exists();

        return $currentUserHasOrganizationUser;
    }
}
