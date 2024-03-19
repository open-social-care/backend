<?php

namespace App\Actions\Admin\User;

use App\DTO\Admin\UserDTO;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserCreateAction
{
    /**
     * Exec$organization = ute create of user with roles and organizations
     */
    public static function execute(UserDTO $userDTO): void
    {
        DB::beginTransaction();

        $userData = $userDTO->toArray();
        User::create($userData);

        DB::commit();
    }
}
