<?php

namespace Tests\Feature\Controllers\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class NewPasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_password_can_be_set()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->createOneQuietly();
        $token = Password::createToken($user);

        $response = $this->postJson(route('reset-password'), [
            'email' => $user->email,
            'token' => $token,
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response->assertJson(['status' => __('passwords.reset')]);

        $this->assertTrue(Hash::check('new-password', $user->fresh()->password));
    }

    public function test_invalid_token_returns_validation_error()
    {
        $user = User::factory()->createOneQuietly();

        $response = $this->postJson(route('reset-password'), [
            'email' => $user->email,
            'token' => 'invalid-token',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response->assertJson(['message' => __('passwords.token')]);
    }
}
