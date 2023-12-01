<?php

namespace App\Actions\Admin\User;

use App\DTO\Admin\UserDTO;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserCreateAction
{
    /**
     * Execute create of user with roles and organizations
     *
     * @param UserDTO $userDTO
     * @return void
     */
    public static function execute(UserDTO $userDTO): void
    {
        DB::beginTransaction();

        $userData = $userDTO->toArray();
        unset($userData['roles']);
        unset($userData['organizations']);

        $user = User::create($userData);
        $user->roles()->attach($userDTO->roles);
        $user->organizations()->attach($userDTO->organizations);

        DB::commit();
    }
}
