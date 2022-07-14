<?php

namespace Hitocean\LaravelAuth\Tests\Actions\User\Auth\CreateTokenFromCredentials;

use Hitocean\LaravelAuth\Auth\Actions\CreateTokenFromCredentialsAction;
use Hitocean\LaravelAuth\Auth\Actions\DTOS\CreateTokenFromCredentialsDTO;
use Hitocean\LaravelAuth\Auth\Exceptions\EmailVerificationException;
use Hitocean\LaravelAuth\Tests\TestCase;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateTokenFromCredentialsActionTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(CreateTokenFromCredentialsSeeder::class);
    }

    /** @test */
    public function succesfull_login_must_return_token()
    {
        $dto = new CreateTokenFromCredentialsDTO(
            email: 'joaquin@vadiun.com',
            password: 'joaquin'
        );
        $token = CreateTokenFromCredentialsAction::make()->handle($dto);
        self::assertIsString($token);
    }

    /** @test */
    public function wrong_credentials_must_throw_validation_exception()
    {
        $dto = new CreateTokenFromCredentialsDTO(
            email: 'joaquin@vadiun.com',
            password: 'joaquinsote'
        );
        $this->expectErrorMessage('El usuario o contraseña son incorrectos');
        $this->expectException(ModelNotFoundException::class);
        CreateTokenFromCredentialsAction::make()->handle($dto);
    }

    /** @test */
    public function credentials_with_no_verified_email_must_throw_email_verification_exception()
    {
        $dto = new CreateTokenFromCredentialsDTO(
            email: 'federico@vadiun.com',
            password: 'federico'
        );
        $this->expectErrorMessage('Por favor verifique su email');
        $this->expectException(EmailVerificationException::class);
        CreateTokenFromCredentialsAction::make()->handle($dto);
    }
}
