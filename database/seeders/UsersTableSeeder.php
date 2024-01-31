<?php

namespace Database\Seeders;

use App\Enums\RolesEnum;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->createAdminUsers();
    }

    private function createAdminUsers(): void
    {
        $users = [
            [
                'name' => 'Admin Social Care',
                'email' => 'admin@socialcare.com',
                'password' => Hash::make('12345678'),
            ],
        ];

        $role = Role::query()->firstWhere('name', RolesEnum::ADMIN);

        foreach ($users as $user) {
            $user = User::query()->firstOrCreate([
                'email' => $user['email'],
            ], $user);

            $user->roleUsers()->firstOrCreate(['role_id' => $role['id']]);
        }
    }
}
