<?php

namespace App\Actions\Admin\Organization;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OrganizationDisassociateUsersWithRolesAction
{
    /**
     * Execute create of organizations
     */
    public static function execute(array $data, Organization $organization): void
    {

        DB::beginTransaction();

        foreach ($data as $datum) {
            self::handleUserOrganizationDetach($datum['user_id'], $datum['role_id'], $organization);
            self::handleUserRoleDetach($datum['user_id'], $datum['role_id']);
        }

        DB::commit();
    }

    /**
     * handle user organization attach with role
     */
    private static function handleUserOrganizationDetach(int $userId, int $roleId, Organization $organization): void
    {
        $organization->users()
            ->wherePivot('user_id', $userId)
            ->wherePivot('role_id', $roleId)
            ->detach();
    }

    /**
     * handle user role attach
     */
    private static function handleUserRoleDetach(int $userId, int $roleId): void
    {
        $user = User::query()->find($userId);
        $user->roles()->wherePivot('role_id', $roleId)->detach();
    }
}
