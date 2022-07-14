<?php

use Hitocean\LaravelAuth\Auth\Actions\DTOS\RegisterUserDTO;
use Hitocean\LaravelAuth\Auth\Actions\RegisterUserAction;
use Hitocean\LaravelAuth\Auth\Notifications\ApiVerifyEmail;
use Hitocean\LaravelAuth\User\Role\Enums\Roles;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;

it('can register', function () {
    $roleAdmin = Role::create(['name' => Roles::SUPER_ADMIN]);

    $dto = new RegisterUserDTO(password: "hola", name: "joaquin", email: "joaquin@vadiun.com");

    Notification::fake();

//    $mock = RegisterUserAction::partialMock()
//                      ->shouldReceive('handle')
//                      ->once()
//                      ->withArgs(fn ($d) => $d == $dto)
//                      ->andReturn(1);

    mock(RegisterUserAction::class)
        ->shouldReceive('handle')
        ->andReturn(true);
    $user = RegisterUserAction::make()->handle($dto);

    Notification::assertSentTo([$user], ApiVerifyEmail::class);

    $this->assertDatabaseHas('users', ['name' => $dto->name, 'email' => $dto->email]);
    $this->assertDatabaseHas('model_has_roles', ['role_id' => $roleAdmin->id, 'model_type' => 'Hitocean\LaravelAuth\User\User\Models\User', 'model_id' => $user->id]);
});
