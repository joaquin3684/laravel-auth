<?php

namespace Hitocean\LaravelAuth\Tests\Actions\User\Auth\RegisterUser;

use Hitocean\LaravelAuth\Auth\Actions\DTOS\RegisterUserDTO;
use Hitocean\LaravelAuth\Auth\Actions\RegisterUserAction;
use Hitocean\LaravelAuth\Auth\FormRequests\RegisterUserFormRequest;
use Hitocean\LaravelAuth\Tests\TestCase;
use Hitocean\LaravelAuth\User\Role\Enums\Roles;
use Hitocean\LaravelAuth\User\User\Models\User;
use Illuminate\Support\Facades\App;

class RegisterUserControllerTest extends TestCase
{
    /** @test */
    public function succesfull_register_user()
    {
        $password = $this->faker->password;
        $data = [
            'email' => $this->faker->email,
            'name' => $this->faker->name,
            'password' => $password,
            'password_confirmation' => $password,
        ];
        $dto = new RegisterUserDTO($data);

        $user = User::factory()->withRole(Roles::SUPER_ADMIN)->create();

        $client_mock = \Mockery::mock(RegisterUserAction::class);
        $client_mock->makePartial()->shouldReceive('handle')->with($data)->andReturn(2);
        App::instance(RegisterUserAction::class, $client_mock);
        RegisterUserAction::partialMock()->shouldReceive('handle')->withArgs(fn ($d) => $d == $dto)->andReturn($user);

        $this->post('api/register', $data)
             ->assertJson([
                              'id' => $user->id,
                              'name' => $user->name,
                              'email' => $user->email,
                              'roles' => [Roles::SUPER_ADMIN],
                          ])
             ->assertStatus(201);
    }

    /** @test */
    public function assert_form_request_rules_are_correct(): void
    {
        $form = new RegisterUserFormRequest();
        $this->assertEquals([
            'password' => 'required|string|confirmed',
            'email' => 'required|string|email',
            'name' => 'required|string',
            'password_confirmation' => 'required',
        ], $form->rules());
    }

    /** @test */
    public function assert_form_request_authorization_is_true()
    {
        $form = new RegisterUserFormRequest();
        $this->assertTrue($form->authorize());
    }
}
