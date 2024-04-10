<?php

namespace Tests\Unit\Events;

use App\Enums\AuditEventTypesEnum;
use App\Events\AuditCreateEvent;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class AuditCreateEventTest extends TestCase
{
    public function testEventDispatchedWithCorrectParameters()
    {
        Event::fake();

        $user = User::factory()->createOneQuietly();
        $eventType = AuditEventTypesEnum::LOGIN;
        $ipAddress = fake()->ipv6;

        AuditCreateEvent::dispatch($user, $user, $eventType, $ipAddress);

        Event::assertDispatched(AuditCreateEvent::class, function ($event) use ($user, $eventType, $ipAddress) {
            return
                $event->model === $user &&
                $event->user === $user &&
                $event->eventType === $eventType &&
                $event->ipAddress === $ipAddress;
        });
    }
}
