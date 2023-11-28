<?php

namespace Database\Seeders;

use App\Enums\DocumentTypesEnum;
use App\Enums\RolesEnum;
use App\Models\Organization;
use App\Models\Role;
use App\Models\RoleUser;
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

        $organization = $this->createOrganization();
        $this->associateOrganizationToUsers($organization);
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

        $this->createUserByRole($users, RolesEnum::ADMIN);
    }

    private function createManagerUsers(): void
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

    private function createSocialAssistantUsers(): void
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

    private function createUserByRole(array $users, RolesEnum $role): void
    {
        $role = Role::query()->firstWhere('name', $role);

        foreach ($users as $user) {
            $user = User::query()->firstOrCreate([
                'email' => $user['email'],
            ], $user);

            $user->roleUsers()->firstOrCreate(['role_id' => $role['id']]);
        }
    }

    private function createOrganization(): Organization
    {
        $organization = [
            'name' => 'Organização Social Care',
            'phone' => '(42) 3333-3333',
            'document_type' => DocumentTypesEnum::CNPJ->value,
            'document' => '12.345.678/0001-00',
        ];

        $organization = Organization::query()->firstOrCreate([
            'document' => $organization['document'],
        ], $organization);

        return $organization;
    }

    private function associateOrganizationToUsers(Organization $organization): void
    {
        $rolesIds = Role::query()
            ->whereIn('name', [RolesEnum::MANAGER->value, RolesEnum::SOCIAL_ASSISTANT->value])
            ->pluck('id');

        $usersIdsToAttach = RoleUser::query()
            ->whereIn('role_id', $rolesIds)
            ->pluck('user_id')
            ->toArray();

        foreach ($usersIdsToAttach as $userId) {
            $existingEntry = $organization->organizationUsers()
                ->where('user_id', $userId)
                ->first();

            if (!$existingEntry) {
                $organization->organizationUsers()->create(['user_id' => $userId]);
            }
        }
    }
}
