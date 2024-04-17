<?php

namespace App\Actions\Manager\User;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserDisassociateFromOrganizationAction
{
    /**
     * Execute update of user with sync roles and organizations
     */
    public static function execute(User $user, Organization $organization): void
    {
        DB::beginTransaction();

        self::handleDetachRoleUser($user, $organization);
        $user->organizations()->detach($organization);

        DB::commit();
    }

    private static function handleDetachRoleUser(User $user, Organization $organization): void
    {
        $userOrganizationRoleId = $user->organizations()
            ->withPivot(['role_id', 'organization_id'])
            ->firstWhere('organization_id', $organization->id)
            ->pivot
            ->role_id;

        $userOrganizationsByRoleCount = $user->organizations()
            ->wherePivot('role_id', $userOrganizationRoleId)
            ->count();

        if ($userOrganizationsByRoleCount == 1) {
            $user->roles()->detach($userOrganizationRoleId);
        }
    }
}
