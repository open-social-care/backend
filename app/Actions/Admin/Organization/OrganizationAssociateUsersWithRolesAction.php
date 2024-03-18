<?php

namespace App\Actions\Admin\Organization;

use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OrganizationAssociateUsersWithRolesAction
{
    /**
     * Execute create of organizations
     */
    public static function execute(User $user, Role $role, Organization $organization): void
    {

        DB::beginTransaction();

        self::handleUserOrganizationAttach($user, $role, $organization);
        self::handleUserRoleAttach($user, $role);

        DB::commit();
    }

    /**
     * handle user organization attach with role
     */
    private static function handleUserOrganizationAttach(User $user, Role $role, Organization $organization): void
    {
        $hasAttach = $organization->users()
            ->wherePivot('user_id', $user->id)
            ->wherePivot('role_id', $role->id)
            ->exists();

        if (!$hasAttach) {
            $organization->users()->attach($user->id, ['role_id' => $role->id]);
        }
    }

    /**
     * handle user role attach
     */
    private static function handleUserRoleAttach(User $user, Role $role): void
    {
        if (!$user->hasRoleById($role->id)) {
            $user->roles()->attach($role->id);
        }
    }
}
