<?php

namespace Hitocean\LaravelAuth\Tests\Actions\User\Auth\ResendEmailVerification;

use Database\Seeders\ResendEmailVerificationSeeder;
use Hitocean\LaravelAuth\Auth\Actions\ResendVerificationEmailAction;
use Hitocean\LaravelAuth\User\User\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ResendEmailVerificationActionTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(ResendEmailVerificationSeeder::class);
    }

    /** @test */
    public function resend_email()
    {
        $unverifiedUser = User::whereNull('email_verified_at')->first();
        \Notification::fake();
        ResendVerificationEmailAction::make()->handle($unverifiedUser->email);
        \Notification::assertSentTo([$unverifiedUser], VerifyEmail::class);
    }

    /** @test */
    public function resend_email_on_verified_user_musnt_send_notification()
    {
        $verifiedUser = User::whereNotNull('email_verified_at')->first();
        \Notification::fake();
        ResendVerificationEmailAction::make()->handle($verifiedUser->email);
        \Notification::assertNothingSent();
    }
}
