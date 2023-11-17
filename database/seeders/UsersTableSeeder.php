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
        $this->createManagerUsers();
        $this->createSocialAssistantUsers();
    }

    private function createAdminUsers()
    {
        $users = [
            [
                'name' => 'Admin Social Care',
                'email' => 'admin@socialcare.com',
                'password' => Hash::make('12345678'),
            ],
        ];

        $this->createUserByRole($users, RolesEnum::ADMIN);
    }

    private function createManagerUsers()
    {
        $users = [
            [
                'name' => 'Manager Social Care',
                'email' => 'manager@socialcare.com',
                'password' => Hash::make('12345678'),
            ],
        ];

        $this->createUserByRole($users, RolesEnum::MANAGER);
    }

    private function createSocialAssistantUsers()
    {
        $users = [
            [
                'name' => 'Social Assistant Social Care',
                'email' => 'social_assistant@socialcare.com',
                'password' => Hash::make('12345678'),
            ],
        ];

        $this->createUserByRole($users, RolesEnum::SOCIAL_ASSISTANT);
    }

    private function createUserByRole(array $users, RolesEnum $role)
    {
        $role = Role::query()->firstWhere('name', $role);

        foreach ($users as $user) {
            $user = User::query()->firstOrCreate([
                'email' => $user['email'],
            ], $user);

            $user->roleUsers()->firstOrCreate(['role_id' => $role['id']]);
        }
    }
}
