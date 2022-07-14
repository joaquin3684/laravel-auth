<?php

namespace Hitocean\LaravelAuth\Tests\Actions\User\User\DeleteUser;

use Hitocean\LaravelAuth\User\User\Actions\DeleteUserAction;
use Hitocean\LaravelAuth\User\User\Models\User;
use Tests\TestCase;

class DeleteUserActionTest extends TestCase
{
    /** @test */
    public function delete_user()
    {
        $id = User::factory()->create()->id;
        DeleteUserAction::make()->handle($id);
        $this->assertSoftDeleted('users', ['id' => $id]);
    }
}
