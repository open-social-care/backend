<?php

namespace App\Actions\Admin\User;

use App\DTO\Admin\UserDTO;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserUpdateAction
{
    /**
     * Execute update of user with sync roles and organizations
     *
     * @param UserDTO $userDTO
     * @param User $user
     * @return void
     */
    public static function execute(UserDTO $userDTO, User $user): void
    {
        DB::beginTransaction();

        $userData = $userDTO->toArray();
        unset($userData['roles']);
        unset($userData['organizations']);

        $user->update($userData);
        $user->roles()->sync($userDTO->roles);
        $user->organizations()->sync($userDTO->organizations);

        DB::commit();
    }
}
