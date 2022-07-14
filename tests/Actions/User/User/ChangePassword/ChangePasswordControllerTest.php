<?php

namespace Hitocean\LaravelAuth\Tests\Actions\User\User\ChangePassword;

use Illuminate\Support\Facades\Gate;
use Mockery\MockInterface;
use Hitocean\LaravelAuth\User\Role\Enums\Roles;
use Hitocean\LaravelAuth\User\User\Actions\ChangePasswordAction;
use Hitocean\LaravelAuth\User\User\Actions\DTOS\ChangePasswordDTO;
use Hitocean\LaravelAuth\User\User\Requests\ChangePasswordRequest;
use Tests\NeedsFormRequests;
use Tests\TestCase;
use Tests\UserMustBeAuthenticated;
use Tests\UserMustBeVerified;

class ChangePasswordControllerTest extends TestCase implements UserMustBeAuthenticated, NeedsFormRequests, UserMustBeVerified
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
    public function change_password($rol)
    {
        $data = [
            'password' => $this->faker->name,
            'id' => 1
        ];
        $this->basicPutAssert(
            $data,
            ChangePasswordAction::class,
            ChangePasswordDTO::class,
            "api/users/1/change-password",
            $rol
        );
    }

    /** @test */
    public function user_must_be_authenticated_exception()
    {
        $this->assert_route_needs_logged_user('put', 'api/users/{id}/change-password');
    }

    /** @test */
    public function user_must_be_verified_exception()
    {
        $this->assert_route_needs_verified_user('put', 'api/users/{id}/change-password');
    }

    /** @test */
    public function unaccepted_roles()
    {
        $this->assert_unaccepted_roles('put', 'api/users/{id}/change-password');
    }

    /** @test */
    public function controller_uses_correct_form_request()
    {
        $this->assertActionUsesFormRequest(ChangePasswordAction::class, ChangePasswordRequest::class);
    }

    /** @test */
    public function form_request_rules_are_correct()
    {
        $request = new ChangePasswordRequest;
        $this->assertEquals([
                                'password' => 'required|string'

                            ], $request->rules());
    }

    /** @test */
    public function form_request_authorization_is_true()
    {
        $request = $this->partialMock(
            ChangePasswordRequest::class,
            fn (MockInterface $m) => $m->shouldReceive('user')->andReturn(1)
        );
        Gate::shouldReceive('check')->with('change-password', 1)->andReturn(true);
        $this->assertTrue($request->authorize());
    }

}
