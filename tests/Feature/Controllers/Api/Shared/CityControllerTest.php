<?php

namespace Feature\Controllers\Api\Shared;

use App\Models\City;
use App\Models\State;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Tests\TestCase;

class CityControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndexMethod()
    {
        $user = User::factory()->createQuietly();
        $this->actingAs($user);

        $states = State::factory()->count(5)->create();
        $stateForTest = $states[0];
        $cities = City::factory()->for($stateForTest)->count(5)->create();

        $response = $this->getJson(route('cities.index', $stateForTest->id));

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                    ],
                ],
            ]);

        foreach ($cities as $city) {
            $response->assertJsonFragment([
                'id' => $city->id,
                'name' => $city->name,
            ]);
        }
    }

    public function testIndexMethodWhenNotAuthorized()
    {
        $states = State::factory()->count(5)->create();
        $stateForTest = $states[0];
        City::factory()->for($stateForTest)->count(5)->create();

        $response = $this->getJson(route('cities.index', $stateForTest->id));

        $response->assertStatus(HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        $response->assertJsonFragment(['message' => 'Unauthenticated.']);
    }
}
