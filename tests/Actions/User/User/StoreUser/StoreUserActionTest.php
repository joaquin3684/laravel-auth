<?php

namespace Hitocean\LaravelAuth\Tests\Actions\User\User\StoreUser;

use Spatie\Permission\Models\Role;
use Hitocean\LaravelAuth\User\Role\Enums\Roles;
use Hitocean\LaravelAuth\User\User\Actions\DTOS\StoreUserDTO;
use Hitocean\LaravelAuth\User\User\Actions\StoreUserAction;
use Tests\TestCase;

class StoreUserActionTest extends TestCase
{

    /** @test */
    public function store_user(): void
    {
        Role::create(['name' => Roles::SUPER_ADMIN]);
        $dto = new StoreUserDTO(
            [
                'name'     => $this->faker->name,
                'email'    => $this->faker->name,
                'password' => $this->faker->name,
                'roles' => [Roles::SUPER_ADMIN]
            ]
        );
        $user = StoreUserAction::make()->handle($dto);
        $this->assertDatabaseHas('users', [
            'name'     => $dto->name,
            'email'    => $dto->email,
            'password' => $dto->password,
        ]);
        $this->assertTrue($user->hasRole(Roles::SUPER_ADMIN));
    }


}
