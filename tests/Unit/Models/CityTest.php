<?php

namespace Tests\Unit\Models;

use App\Models\City;
use App\Models\State;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CityTest extends TestCase
{
    use RefreshDatabase;

    public function testCityBelongsToState()
    {
        $state = State::factory()->createOneQuietly();
        $city = City::factory()->for($state)->createOneQuietly();

        $this->assertEquals($state->id, $city->state->id);
    }
}
