<?php

namespace Tests\Feature\Controllers\Auth;

use App\Mail\SendCodeResetPassword;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Tests\TestCase;

class ForgotPasswordControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_forgot_password_controller_success()
    {
        Mail::fake();

        $user = User::factory()->createOneQuietly();

        $payload = [
            'email' => $user->email,
        ];

        $response = $this->postJson(route('password.send-email'), $payload);

        $response->assertStatus(HttpResponse::HTTP_OK);
        $this->assertDatabaseHas('password_reset_tokens', ['email' => $user->email]);

        Mail::assertSent(SendCodeResetPassword::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    public function test_forgot_password_controller_validation_exception()
    {
        $payload = [
            'email' => $this->faker->email,
        ];

        $response = $this->postJson(route('password.send-email'), $payload);

        $response->assertStatus(HttpResponse::HTTP_UNPROCESSABLE_ENTITY);

        $responseData = $response->json();
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals(__('validation.exists', ['attribute' => 'Email']), $responseData['message']);
    }
}
