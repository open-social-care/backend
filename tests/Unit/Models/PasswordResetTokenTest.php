<?php

namespace Tests\Unit\Models;

use App\Models\PasswordResetToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasswordResetTokenTest extends TestCase
{
    use RefreshDatabase;

    public function testIsExpireWhenFalse()
    {
        $passwordResetToken = PasswordResetToken::factory()->createOneQuietly();
        $this->assertNotTrue($passwordResetToken->isExpire());
    }

    public function testIsExpireWhenTrue()
    {
        $passwordResetToken = PasswordResetToken::factory()->createOneQuietly();
        $passwordResetToken->update(['created_at' => now()->startOfDay()]);
        $this->assertTrue($passwordResetToken->isExpire());
    }
}
