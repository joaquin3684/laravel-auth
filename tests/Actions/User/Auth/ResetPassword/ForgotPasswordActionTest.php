<?php

namespace Hitocean\LaravelAuth\Tests\Actions\User\Auth\ResetPassword;

use Database\Seeders\ResetPasswordSeeder;
use Hitocean\LaravelAuth\Auth\Actions\ForgotPasswordAction;
use Hitocean\LaravelAuth\User\User\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ForgotPasswordActionTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(ResetPasswordSeeder::class);
    }

    /** @test */
    public function forgot_passwrd_must_send_email()
    {
        Notification::fake();
        $email = 'joaquin@vadiun.com';
        $user = User::where('email', $email)->first();

        ForgotPasswordAction::make()->handle($email);
        Notification::assertSentTo($user, ResetPassword::class);
        $this->assertDatabaseHas('password_resets', ['email' => $email]);
    }

    /** @test */
    public function forgot_password_musnt_send_email()
    {
        Notification::fake();
        $email = 'pepe@vadiun.com';

        ForgotPasswordAction::make()->handle($email);

        Notification::assertNothingSent();
        $this->assertDatabaseMissing('password_resets', ['email' => $email]);
    }
}
