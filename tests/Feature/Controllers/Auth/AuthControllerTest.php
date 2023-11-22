<?php

namespace Tests\Feature\Controllers\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user login with valid credentials.
     */
    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create();
        $payload = ['email' => $user->email, 'password' => 'password'];

        $response = $this->postJson(route('login'), $payload);

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJsonStructure(['token', 'message']);
    }

    /**
     * Test user login with invalid credentials.
     */
    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $payload = ['email' => 'nonexistent@example.com', 'password' => 'wrongpassword'];

        $response = $this->postJson(route('login'), $payload);

        $response->assertStatus(HttpResponse::HTTP_UNAUTHORIZED)
            ->assertJson(['message' => __('messages.auth.login_invalid')]);
    }

    /**
     * Test user logout.
     */
    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();
        $user->createToken('auth-token')->plainTextToken;

        $response = $this->actingAs($user)
            ->postJson(route('logout'));

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJson(['message' => __('messages.auth.logout_success')]);
    }
}
