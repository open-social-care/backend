<?php

namespace App\Policies;

use App\Enums\RolesEnum;
use App\Models\Organization;
use App\Models\User;

class OrganizationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRoleByName(RolesEnum::ADMIN->value) || $user->hasRoleByName(RolesEnum::MANAGER->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Organization $organization): bool
    {
        if ($user->hasRoleByName(RolesEnum::ADMIN->value) && $user->organizations->isEmpty()) {
            return true;
        }

        if ($user->hasOrganization($organization->id)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRoleByName(RolesEnum::ADMIN->value) && $user->organizations->isEmpty();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Organization $organization): bool
    {
        if ($user->hasRoleByName(RolesEnum::ADMIN->value) && $user->organizations->isEmpty()) {
            return true;
        }

        if ($user->hasRoleByName(RolesEnum::MANAGER->value) && $user->hasOrganization($organization->id)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Organization $organization): bool
    {
        if ($user->hasRoleByName(RolesEnum::ADMIN->value) && $user->organizations->isEmpty()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can associate users the model.
     */
    public function associateUsers(User $user, Organization $organization): bool
    {
        if ($user->hasRoleByName(RolesEnum::ADMIN->value) && $user->organizations->isEmpty()) {
            return true;
        }

        if ($user->hasRoleByName(RolesEnum::MANAGER->value) && $user->hasOrganization($organization->id)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can associate users the model.
     */
    public function disassociateUsers(User $user, Organization $organization): bool
    {
        if ($user->hasRoleByName(RolesEnum::ADMIN->value) && $user->organizations->isEmpty()) {
            return true;
        }

        if ($user->hasRoleByName(RolesEnum::MANAGER->value) && $user->hasOrganization($organization->id)) {
            return true;
        }

        return false;
    }
}
