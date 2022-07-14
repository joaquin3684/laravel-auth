<?php

namespace Hitocean\LaravelAuth\Tests\Actions\User\User\UpdateUser;

use Hitocean\LaravelAuth\User\Role\Enums\Roles;
use Hitocean\LaravelAuth\User\User\Actions\DTOS\UpdateUserDTO;
use Hitocean\LaravelAuth\User\User\Actions\UpdateUserAction;
use Hitocean\LaravelAuth\User\User\Models\User;
use Tests\TestCase;

class UpdateUserActionTest extends TestCase
{
    /** @test */
    public function update_user(): void
    {
        $user = User::factory()->withRole(Roles::SUPER_ADMIN)->create();

        $dto = new UpdateUserDTO(
            [
                'id' => $user->id,
                'name' => $this->faker->name,
                'email' => $this->faker->name,
                'roles' => [Roles::SUPER_ADMIN],
            ]
        );
        $user = UpdateUserAction::make()->handle($dto);
        $this->assertDatabaseHas('users', [
            'name' => $dto->name,
            'email' => $dto->email,

        ]);
        $this->assertTrue($user->hasRole(Roles::SUPER_ADMIN));
    }
}
