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
                'name' => RolesEnum::ADMIN->value,
            ],
            [
                'name' => RolesEnum::MANAGER->value,
            ],
            [
                'name' => RolesEnum::SOCIAL_ASSISTANT->value,
            ],
        ];

        foreach ($data as $dataToCreate) {
            Role::query()->firstOrCreate($dataToCreate);
        }
    }
}
