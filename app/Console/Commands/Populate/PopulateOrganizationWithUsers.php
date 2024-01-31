<?php

namespace App\Console\Commands\Populate;

use App\Enums\DocumentTypesEnum;
use App\Enums\RolesEnum;
use App\Models\Organization;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PopulateOrganizationWithUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'populate:organization-with-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate organization with users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Populating organization data with users!');
        DB::beginTransaction();

        $this->createManagerUsers();
        $this->createSocialAssistantUsers();

        $organization = $this->createOrganization();
        $this->associateOrganizationToUsers($organization);

        DB::commit();
        $this->info('Finished organization population with users!');
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
