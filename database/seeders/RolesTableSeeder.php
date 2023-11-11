<?php

namespace Database\Seeders;

use App\Enums\RolesEnum;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => RolesEnum::ADMIN,
            ],
            [
                'name' => RolesEnum::MANAGER,
            ],
            [
                'name' => RolesEnum::SOCIAL_ASSISTANT,
            ],
        ];

        foreach ($data as $dataToCreate) {
            Role::query()->firstOrCreate($dataToCreate);
        }
    }
}
