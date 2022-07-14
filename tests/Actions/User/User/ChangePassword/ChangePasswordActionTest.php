<?php

namespace Hitocean\LaravelAuth\Tests\Actions\User\User\ChangePassword;

use Hash;
use Hitocean\LaravelAuth\User\Role\Enums\Roles;
use Hitocean\LaravelAuth\User\User\Actions\ChangePasswordAction;
use Hitocean\LaravelAuth\User\User\Actions\DTOS\ChangePasswordDTO;
use Hitocean\LaravelAuth\User\User\Models\User;
use Tests\TestCase;

class ChangePasswordActionTest extends TestCase
{
    /** @test */
    public function change_password()
    {
        $user = User::factory()->withRole(Roles::SUPER_ADMIN)->create();
        $dto = new ChangePasswordDTO([
            'id' => $user->id,
            'password' => 1234,
                                     ]);
        ChangePasswordAction::make()->handle($dto);
        $user = $user->fresh();
        $this->assertTrue(Hash::check(1234, $user->password));
    }
}
