<?php

namespace Hitocean\LaravelAuth\Tests\Actions\User\User\GetUser;

use Illuminate\Support\Facades\Gate;
use Mockery\MockInterface;
use Hitocean\LaravelAuth\User\Role\Enums\Roles;
use Hitocean\LaravelAuth\User\User\Actions\GetUserAction;
use Hitocean\LaravelAuth\User\User\Models\User;
use Hitocean\LaravelAuth\User\User\Requests\GetUserRequest;
use Tests\NeedsFormRequests;
use Tests\ResourceHelpers\User\UserResourceHelperTest;
use Tests\TestCase;
use Tests\UserMustBeAuthenticated;
use Tests\UserMustBeVerified;

class GetUserControllerTest extends TestCase implements UserMustBeAuthenticated, NeedsFormRequests, UserMustBeVerified
{
    public function acceptedRoles(): array
    {
        return [
            [Roles::SUPER_ADMIN],
        ];
    }

    /**
     * @test
     * @dataProvider acceptedRoles
     * @param $rol
     */
    public function get_user($rol): void
    {
        $user = User::factory(2)->withRole(Roles::SUPER_ADMIN)->create();
        $expectedJson = UserResourceHelperTest::complete($user);
        $this->basicGetAssert(GetUserAction::class, "api/users", $rol, $expectedJson, null, null, null, $user[0]);
    }

    /** @test */
    public function user_must_be_authenticated_exception(): void
    {
        $this->assert_route_needs_logged_user('get', 'api/users');
    }

    /** @test */
    public function user_must_be_verified_exception(): void
    {
        $this->assert_route_needs_verified_user('get', 'api/users');
    }

    /** @test */
    public function unaccepted_roles(): void
    {
        $this->assert_unaccepted_roles('get', 'api/users');
    }

    /** @test */
    public function controller_uses_correct_form_request(): void
    {
        $this->assertActionUsesFormRequest(GetUserAction::class, GetUserRequest::class);
    }

    /** @test */
    public function form_request_rules_are_correct(): void
    {
        $request = new GetUserRequest();
        $this->assertEquals([], $request->rules());
    }

    /** @test */
    public function form_request_authorization_is_true(): void
    {
        /** @var GetUserRequest $request */
        $request = $this->partialMock(
            GetUserRequest::class,
            fn (MockInterface $m) => $m->shouldReceive('user')->andReturn(1)
        );
        Gate::shouldReceive('check')->with('get-user', 1)->andReturn(true);
        $this->assertTrue($request->authorize());
    }


}
