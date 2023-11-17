<?php

namespace Tests\Feature\Controllers\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PasswordResetLinkControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_password_reset_link_can_be_requested()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->createOneQuietly();

        $response = $this->postJson(route('forgot-password'), [
            'email' => $user->email,
        ]);

        $response->assertJson(['status' => __('passwords.sent')]);

        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => $user->email,
        ]);
    }

    public function test_invalid_email_returns_validation_error()
    {
        $response = $this->postJson(route('forgot-password'), [
            'email' => 'invalid-email',
        ]);

        $response->assertJsonValidationErrors(['email']);
    }
}
