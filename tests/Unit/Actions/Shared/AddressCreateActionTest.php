<?php

namespace Tests\Unit\Actions\Shared;

use App\Actions\Shared\AddressCreateAction;
use App\DTO\Shared\AddressDTO;
use App\Models\City;
use App\Models\State;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddressCreateActionTest extends TestCase
{
    use RefreshDatabase;

    public function testExecuteAction()
    {
        $state = State::factory()->createOneQuietly();
        $city = City::factory()->createOneQuietly();
        $subject = Subject::factory()->createOneQuietly();

        $data = [
            'street' => fake()->name,
            'district' => fake()->name,
            'number' => fake()->numberBetween(),
            'state_id' => $state->id,
            'city_id' => $city->id,
        ];

        $dto = new AddressDTO($data, $subject);

        AddressCreateAction::execute($dto);

        $this->assertDatabaseHas('addresses', ['street' => $dto->street]);
    }
}
