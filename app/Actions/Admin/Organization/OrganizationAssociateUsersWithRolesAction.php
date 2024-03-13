<?php

namespace App\Actions\Admin\Organization;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OrganizationAssociateUsersWithRolesAction
{
    /**
     * Execute create of organizations
     */
    public static function execute(array $data, Organization $organization): void
    {

        DB::beginTransaction();

        foreach ($data as $datum) {
            self::handleUserOrganizationAttach($datum['user_id'], $datum['role_id'], $organization);
            self::handleUserRoleAttach($datum['user_id'], $datum['role_id']);
        }

        DB::commit();
    }

    /**
     * handle user organization attach with role
     */
    private static function handleUserOrganizationAttach(int $userId, int $roleId, Organization $organization): void
    {
        $hasAttach = $organization->users()
            ->wherePivot('user_id', $userId)
            ->wherePivot('role_id', $roleId)
            ->exists();

        if (!$hasAttach) {
            $organization->users()->attach($userId, ['role_id' => $roleId]);
        }
    }

    /**
     * handle user role attach
     */
    private static function handleUserRoleAttach(int $userId, int $roleId): void
    {
        $user = User::query()->find($userId);

        if (!$user->hasRoleById($roleId)) {
            $user->roles()->attach($roleId);
        }
    }
}
