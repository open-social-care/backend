<?php

namespace App\Actions\Manager\User;

use App\DTO\Shared\UserDTO;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserUpdateAction
{
    /**
     * Execute update of user with sync roles and organizations
     */
    public static function execute(UserDTO $userDTO, User $user): void
    {
        DB::beginTransaction();

        $userData = $userDTO->toArray();

        $userData['password'] = ! $userData['password'] ? $user->getAuthPassword() : $userData['password'];
        $user->update($userData);

        DB::commit();
    }
}
