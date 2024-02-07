<?php

namespace App\Actions\Manager\Organization;

use App\DTO\Manager\OrganizationDTO;
use Illuminate\Support\Facades\DB;

class OrganizationUpdateAction
{
    /**
     * Execute update of organization
     */
    public static function execute(OrganizationDTO $dto): void
    {
        DB::beginTransaction();

        $user = auth()->user();
        $organization = $user->organizations->first();
        $organization->update($dto->toArray());

        DB::commit();
    }
}
