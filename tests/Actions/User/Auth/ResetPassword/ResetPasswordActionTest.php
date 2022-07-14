<?php

namespace Hitocean\LaravelAuth\Tests\Actions\User\Auth\ResetPassword;

use Database\Seeders\ResetPasswordSeeder;
use Hitocean\LaravelAuth\Auth\Actions\DTOS\ResetPasswordDTO;
use Hitocean\LaravelAuth\Auth\Actions\ResetPasswordAction;
use Hitocean\LaravelAuth\User\User\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ResetPasswordActionTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(ResetPasswordSeeder::class);
    }

    /** @test */
    public function successfull_reset_password()
    {
        $email = 'joaquin@vadiun.com';
        $user = User::whereEmail($email)->first();
        $token = \Password::createToken($user);
        $dto = new ResetPasswordDTO(
            email: $email,
            token: $token,
            password: "prueba",
            password_confirmation: "prueba"
        );

        $result = ResetPasswordAction::make()->handle($dto);
        $user = $user->fresh();

        $this->assertTrue($result);
        $this->assertTrue(\Hash::check("prueba", $user->password));
    }
}
