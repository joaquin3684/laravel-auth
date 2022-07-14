<?php

namespace Hitocean\LaravelAuth\Tests\Actions\User\User\FindUser;

use Hitocean\LaravelAuth\User\Role\Enums\Roles;
use Hitocean\LaravelAuth\User\User\Actions\FindUserAction;
use Hitocean\LaravelAuth\User\User\Models\User;
use Hitocean\LaravelAuth\User\User\Requests\FindUserRequest;
use Illuminate\Support\Facades\Gate;
use Mockery\MockInterface;
use Tests\NeedsFormRequests;
use Tests\ResourceHelpers\User\UserResourceHelperTest;
use Tests\TestCase;
use Tests\UserMustBeAuthenticated;
use Tests\UserMustBeVerified;

class FindUserControllerTest extends TestCase implements UserMustBeAuthenticated, NeedsFormRequests, UserMustBeVerified
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
    public function find_user($rol)
    {
        $user = User::factory()->create();
        $expectedJson = UserResourceHelperTest::complete($user);
        $this->basicGetAssert(FindUserAction::class, "api/users/{$user->id}", $rol, $expectedJson);
    }

    /** @test */
    public function user_must_be_authenticated_exception()
    {
        $this->assert_route_needs_logged_user('get', 'api/users/{id}');
    }

    /** @test */
    public function user_must_be_verified_exception()
    {
        $this->assert_route_needs_verified_user('get', 'api/users/{id}');
    }

    /** @test */
    public function unaccepted_roles()
    {
        $this->assert_unaccepted_roles('get', 'api/users/{id}');
    }

    /** @test */
    public function controller_uses_correct_form_request()
    {
        $this->assertActionUsesFormRequest(FindUserAction::class, FindUserRequest::class);
    }

    /** @test */
    public function form_request_rules_are_correct()
    {
        $request = new FindUserRequest();
        $this->assertEquals([], $request->rules());
    }

    /** @test */
    public function form_request_authorization_is_true()
    {
        $request = $this->partialMock(FindUserRequest::class, fn (MockInterface $m) => $m->shouldReceive('user')->andReturn(1));
        Gate::shouldReceive('check')->with('find-user', 1)->andReturn(true);
        $this->assertTrue($request->authorize());
    }
}
