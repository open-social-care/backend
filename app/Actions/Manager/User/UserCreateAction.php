<?php

namespace App\Actions\Manager\User;

use App\DTO\Shared\UserDTO;
use App\Enums\RolesEnum;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserCreateAction
{
    /**
     * Exec$organization = ute create of user with roles and organizations
     */
    public static function execute(UserDTO $userDTO, Organization $organization): User
    {
        DB::beginTransaction();

        $userData = $userDTO->toArray();
        $user = User::create($userData);
        $roleSocialAssistant = Role::query()->firstWhere(['name' => RolesEnum::SOCIAL_ASSISTANT->value]);

        self::handleUserOrganizationAttach($user, $organization, $roleSocialAssistant);
        self::handleUserRoleAttach($user, $roleSocialAssistant);

        DB::commit();

        return $user;
    }

    /**
     * handle user organization attach with role
     */
    private static function handleUserOrganizationAttach(User $user, Organization $organization, Role $role): void
    {
        if (! $user->hasOrganization($organization->id)) {
            $organization->users()->attach($user->id, ['role_id' => $role->id]);
        }
    }

    /**
     * handle user role attach
     */
    private static function handleUserRoleAttach(User $user, Role $role): void
    {
        if (! $user->hasRoleById($role->id)) {
            $user->roles()->attach($role->id);
        }
    }
}
