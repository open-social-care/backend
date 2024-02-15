<?php

namespace Feature\Controllers\Api\Auth;

use App\Models\PasswordResetToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Tests\TestCase;

class ResetPasswordControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_reset_password_successfully()
    {
        $user = User::factory()->createOneQuietly();
        $token = (string) mt_rand(100000, 999999);

        PasswordResetToken::create([
            'email' => $user->email,
            'token' => $token,
            'created_at' => now()->addMinutes(20),
        ]);

        $newPassword = $this->faker->password;

        $response = $this->postJson(route('password.reset'), [
            'token' => $token,
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
        ]);

        $response->assertStatus(HttpResponse::HTTP_OK)
            ->assertJson([
                'message' => __('messages.auth.password.password_reset_success'),
            ]);

        $this->assertDatabaseMissing('password_reset_tokens', ['token' => $token]);
        $this->assertTrue(Hash::check($newPassword, $user->fresh()->password));
    }

    public function test_reset_password_with_invalid_token()
    {
        $password = $this->faker->password;

        $response = $this->postJson(route('password.reset'), [
            'token' => 'invalid_token',
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        $response->assertStatus(HttpResponse::HTTP_UNPROCESSABLE_ENTITY);

        $responseData = $response->json();
        $this->assertArrayHasKey('errors', $responseData);
        $this->assertStringContainsString(__('passwords.token_is_invalid'), $responseData['errors']['token'][0]);
    }
}
