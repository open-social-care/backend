<?php

namespace App\Actions\Shared;

use App\DTO\Shared\AddressDTO;
use App\Models\Address;
use Illuminate\Support\Facades\DB;

class AddressCreateAction
{
    /**
     * Execute action
     */
    public static function execute(AddressDTO $addressDTO): Address
    {
        DB::beginTransaction();

        $data = $addressDTO->toArray();
        $address = Address::create($data);

        DB::commit();

        return $address;
    }
}
