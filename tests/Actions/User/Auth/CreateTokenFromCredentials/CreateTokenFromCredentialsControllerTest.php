<?php


namespace Hitocean\LaravelAuth\Tests\Actions\User\Auth\CreateTokenFromCredentials;


use Mockery\MockInterface;
use Hitocean\LaravelAuth\Auth\Actions\CreateTokenFromCredentialsAction;
use Hitocean\LaravelAuth\Auth\Actions\DTOS\CreateTokenFromCredentialsDTO;
use Hitocean\LaravelAuth\Auth\FormRequests\CreateTokenFromCredentialsFormRequest;
use Hitocean\LaravelAuth\User\User\Models\User;
use Tests\TestCase;

class CreateTokenFromCredentialsControllerTest extends TestCase
{
    /** @test */
    public function correct_credentials()
    {
        $data = ['email' => $this->faker->email, 'password' => $this->faker->word];
        $dto = new CreateTokenFromCredentialsDTO($data);

        CreateTokenFromCredentialsAction::partialMock()->shouldReceive('handle')->withArgs(fn($var) => $var == $dto)->andReturn('hola como estas');
        $this->post('api/login', $data)
            ->assertJson(['token' => 'hola como estas']);

    }

    /** @test */
    public function assert_controller_uses_correct_form_request()
    {
        $this->assertActionUsesFormRequest(CreateTokenFromCredentialsAction::class, CreateTokenFromCredentialsFormRequest::class);
    }

    /** @test */
    public function assert_form_request_rules_are_correct()
    {
        $form = new CreateTokenFromCredentialsFormRequest();
        $this->assertEquals([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ], $form->rules());
    }

    /** @test */
    public function assert_form_request_authorization_is_true()
    {
        $form = new CreateTokenFromCredentialsFormRequest();
        $this->assertTrue($form->authorize());
    }

}
