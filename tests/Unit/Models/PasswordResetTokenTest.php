<?php

namespace Tests\Unit\Models;

use App\Models\PasswordResetToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasswordResetTokenTest extends TestCase
{
    use RefreshDatabase;

    public function testIsExpiredWhenFalse()
    {
        $passwordResetToken = PasswordResetToken::factory()->createOneQuietly();
        $this->assertNotTrue($passwordResetToken->isExpired());
    }

    public function testIsExpiredWhenTrue()
    {
        $passwordResetToken = PasswordResetToken::factory()->createOneQuietly();
        $passwordResetToken->update(['created_at' => now()->subMinutes(30)]);
        $this->assertTrue($passwordResetToken->isExpired());
    }
}
