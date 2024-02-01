<?php

namespace App\Actions\Admin\Organization;

use App\DTO\Admin\OrganizationDTO;
use App\Models\Organization;
use Illuminate\Support\Facades\DB;

class OrganizationCreateAction
{
    /**
     * Execute create of organizations
     */
    public static function execute(OrganizationDTO $dto): void
    {
        DB::beginTransaction();

        Organization::create($dto->toArray());

        DB::commit();
    }
}
