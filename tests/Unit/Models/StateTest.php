<?php

namespace Tests\Unit\Models;

use App\Models\Address;
use App\Models\City;
use App\Models\State;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StateTest extends TestCase
{
    use RefreshDatabase;

    public function testStateHasManyAddresses()
    {
        $state = State::factory()->createOneQuietly();
        $address1 = Address::factory()->createOneQuietly(['state_id' => $state->id]);
        $address2 = Address::factory()->createOneQuietly(['state_id' => $state->id]);

        $this->assertTrue($state->addresses->contains($address1));
        $this->assertTrue($state->addresses->contains($address2));
    }

    public function testStateHasManyCities()
    {
        $state = State::factory()->createOneQuietly();
        $city1 = City::factory()->createOneQuietly(['state_id' => $state->id]);
        $city2 = City::factory()->createOneQuietly(['state_id' => $state->id]);

        $this->assertTrue($state->cities->contains($city1));
        $this->assertTrue($state->cities->contains($city2));
    }
}