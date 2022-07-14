<?php

namespace Hitocean\LaravelAuth\Tests\Actions\User\User\GetRoles;

use Hitocean\LaravelAuth\User\Role\Enums\Roles;
use Hitocean\LaravelAuth\User\User\Actions\GetRolesAction;
use Hitocean\LaravelAuth\User\User\Requests\GetRolesRequest;
use Illuminate\Support\Facades\Gate;
use Mockery\MockInterface;
use Tests\NeedsFormRequests;
use Tests\TestCase;
use Tests\UserMustBeAuthenticated;
use Tests\UserMustBeVerified;

class GetRolesControllerTest extends TestCase implements UserMustBeAuthenticated, NeedsFormRequests, UserMustBeVerified
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
    public function get_roles($rol)
    {
        $this->basicGetAssert(GetRolesAction::class, "api/roles", $rol, Roles::all());
    }

    /** @test */
    public function user_must_be_authenticated_exception()
    {
        $this->assert_route_needs_logged_user('get', 'api/roles');
    }

    /** @test */
    public function user_must_be_verified_exception()
    {
        $this->assert_route_needs_verified_user('get', 'api/roles');
    }

    /** @test */
    public function unaccepted_roles()
    {
        $this->assert_unaccepted_roles('get', 'api/roles');
    }

    /** @test */
    public function controller_uses_correct_form_request()
    {
        $this->assertActionUsesFormRequest(GetRolesAction::class, GetRolesRequest::class);
    }

    /** @test */
    public function form_request_rules_are_correct()
    {
        $request = new GetRolesRequest();
        $this->assertEquals([], $request->rules());
    }

    /** @test */
    public function form_request_authorization_is_true()
    {
        $request = $this->partialMock(GetRolesRequest::class, fn (MockInterface $m) => $m->shouldReceive('user')->andReturn(1));
        Gate::shouldReceive('check')->with('get-roles', 1)->andReturn(true);
        $this->assertTrue($request->authorize());
    }
}
