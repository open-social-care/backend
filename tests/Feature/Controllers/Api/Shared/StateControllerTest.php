<?php

namespace Feature\Controllers\Api\Shared;

use App\Models\State;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Tests\TestCase;

class StateControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndexMethod()
    {
        $user = User::factory()->createQuietly();
        $this->actingAs($user);

        $states = State::factory()->count(5)->create();
        $response = $this->getJson(route('states.index'));

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                    ],
                ],
            ]);

        foreach ($states as $state) {
            $response->assertJsonFragment([
                'id' => $state->id,
                'name' => $state->name,
            ]);
        }
    }

    public function testIndexMethodWhenNotAuthorized()
    {
        State::factory()->count(5)->create();
        $response = $this->getJson(route('states.index'));

        $response->assertStatus(HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        $response->assertJsonFragment(['message' => 'Unauthenticated.']);
    }
}
