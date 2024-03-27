<?php

namespace Tests\Unit\Models;

use App\Models\Audit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditTest extends TestCase
{
    use RefreshDatabase;

    public function testModelBelongsToAudit()
    {
        $user = User::factory()->createOneQuietly();
        $audit = Audit::factory()->createOneQuietly([
            'model_id' => $user->id,
            'model_type' => User::class,
        ]);

        $this->assertEquals($user->id, $audit->model_id);
        $this->assertEquals(User::class, $audit->model_type);
    }

    public function testUserBelongsToAudit()
    {
        $user = User::factory()->createOneQuietly();
        $audit = Audit::factory()->for($user)->createOneQuietly();

        $this->assertEquals($user->id, $audit->user_id);
    }
}
