<?php

namespace App\Actions\Admin\Organization;

use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OrganizationDisassociateUsersWithRolesAction
{
    /**
     * Execute create of organizations
     */
    public static function execute(User $user, Role $role, Organization $organization): void
    {

        DB::beginTransaction();

        self::handleUserOrganizationDetach($user, $role, $organization);
        self::handleUserRoleDetach($user, $role);

        DB::commit();
    }

    /**
     * handle user organization attach with role
     */
    private static function handleUserOrganizationDetach(User $user, Role $role, Organization $organization): void
    {
        $organization->users()
            ->wherePivot('user_id', $user->id)
            ->wherePivot('role_id', $role->id)
            ->detach();
    }

    /**
     * handle user role attach
     */
    private static function handleUserRoleDetach(User $user, Role $role): void
    {
        $user->roles()->wherePivot('role_id', $role->id)->detach();
    }
}
