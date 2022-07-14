<?php

namespace Hitocean\LaravelAuth\Tests\Actions\User\User\DeleteUser;

use Hitocean\LaravelAuth\User\Role\Enums\Roles;
use Hitocean\LaravelAuth\User\User\Actions\DeleteUserAction;
use Hitocean\LaravelAuth\User\User\Models\User;
use Hitocean\LaravelAuth\User\User\Requests\DeleteUserRequest;
use Illuminate\Support\Facades\Gate;
use Mockery\MockInterface;
use Tests\NeedsFormRequests;
use Tests\TestCase;
use Tests\UserMustBeAuthenticated;
use Tests\UserMustBeVerified;

class DeleteUserControllerTest extends TestCase implements UserMustBeAuthenticated, NeedsFormRequests, UserMustBeVerified
{
    public function acceptedRoles()
    {
        return [
            [Roles::SUPER_ADMIN],
        ];
    }

    /**
     * @test
     * @dataProvider acceptedRoles
     */
    public function delete_user($rol)
    {
        $user = User::factory()->create();
        $this->basicDeleteAssert($user->id, DeleteUserAction::class, null, "api/users/{$user->id}", $rol);
    }

    /** @test */
    public function user_must_be_authenticated_exception()
    {
        $this->assert_route_needs_logged_user('delete', 'api/users/{id}');
    }

    /** @test */
    public function user_must_be_verified_exception()
    {
        $this->assert_route_needs_verified_user('delete', 'api/users/{id}');
    }

    /** @test */
    public function unaccepted_roles()
    {
        $this->assert_unaccepted_roles('delete', 'api/users/{id}');
    }

    /** @test */
    public function controller_uses_correct_form_request()
    {
        $this->assertActionUsesFormRequest(DeleteUserAction::class, DeleteUserRequest::class);
    }

    /** @test */
    public function form_request_rules_are_correct()
    {
        $request = new DeleteUserRequest();
        $this->assertEquals([], $request->rules());
    }

    /** @test */
    public function form_request_authorization_is_true()
    {
        $request = $this->partialMock(DeleteUserRequest::class, fn (MockInterface $m) => $m->shouldReceive('user')->andReturn(1));
        Gate::shouldReceive('check')->with('delete-user', 1)->andReturn(true);
        $this->assertTrue($request->authorize());
    }
}
