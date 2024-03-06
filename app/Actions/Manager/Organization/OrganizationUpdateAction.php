<?php

namespace App\Actions\Manager\Organization;

use App\DTO\Manager\OrganizationDTO;
use App\Models\Organization;
use Illuminate\Support\Facades\DB;

class OrganizationUpdateAction
{
    /**
     * Execute update of organization
     */
    public static function execute(OrganizationDTO $dto, Organization $organization): void
    {
        DB::beginTransaction();

        $organization->update($dto->toArray());

        DB::commit();
    }
}
