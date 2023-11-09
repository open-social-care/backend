<?php

namespace Tests\Unit\Models;

use App\Models\Address;
use App\Models\City;
use App\Models\State;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddressTest extends TestCase
{
    use RefreshDatabase;

    public function testStateBelongsToAddress()
    {
        $state = State::factory()->createOneQuietly();
        $address = Address::factory()->for($state)->createOneQuietly();

        $this->assertEquals($state->id, $address->state->id);
    }

    public function testCityBelongsToAddress()
    {
        $city = City::factory()->createOneQuietly();
        $address = Address::factory()->for($city)->createOneQuietly();

        $this->assertEquals($city->id, $address->city->id);
    }
}
