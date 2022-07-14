<?php

namespace Hitocean\LaravelAuth\Tests\Actions\User\User\StoreUser;

use Illuminate\Support\Facades\Gate;
use Mockery\MockInterface;
use Hitocean\LaravelAuth\User\Role\Enums\Roles;
use Hitocean\LaravelAuth\User\User\Actions\DTOS\StoreUserDTO;
use Hitocean\LaravelAuth\User\User\Actions\StoreUserAction;
use Hitocean\LaravelAuth\User\User\Models\User;
use Hitocean\LaravelAuth\User\User\Requests\StoreUserRequest;
use Tests\NeedsFormRequests;
use Tests\ResourceHelpers\User\UserResourceHelperTest;
use Tests\TestCase;
use Tests\UserMustBeAuthenticated;
use Tests\UserMustBeVerified;

class StoreUserControllerTest extends TestCase implements UserMustBeAuthenticated, NeedsFormRequests, UserMustBeVerified
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
    public function store_user($rol): void
    {
        $data         = [
            'name'     => $this->faker->name,
            'email'    => $this->faker->name,
            'password' => $this->faker->name,
            'roles'    => [Roles::SUPER_ADMIN]
        ];
        $user         = User::factory()->create();
        $expectedJson = UserResourceHelperTest::complete($user);
        $this->basicPostAssert(
            $data,
            StoreUserAction::class,
            StoreUserDTO::class,
            "api/users",
            $rol,
            $expectedJson,
            $user
        );
    }

    /**
     * @test
     * @dataProvider acceptedRoles
     * @param $rol
     */
    public function store_user_nullable($rol): void
    {
        $data         = [
            'name'     => $this->faker->name,
            'email'    => $this->faker->name,
            'password' => $this->faker->name,
            'roles' => [Roles::SUPER_ADMIN]
        ];
        $user         = User::factory()->create();
        $expectedJson = UserResourceHelperTest::complete($user);
        $this->basicPostAssert(
            $data,
            StoreUserAction::class,
            StoreUserDTO::class,
            "api/users",
            $rol,
            $expectedJson,
            $user
        );
    }

    /** @test */
    public function user_must_be_authenticated_exception(): void
    {
        $this->assert_route_needs_logged_user('post', 'api/users');
    }

    /** @test */
    public function user_must_be_verified_exception(): void
    {
        $this->assert_route_needs_verified_user('post', 'api/users');
    }

    /** @test */
    public function unaccepted_roles(): void
    {
        $this->assert_unaccepted_roles('post', 'api/users');
    }

    /** @test */
    public function controller_uses_correct_form_request(): void
    {
        $this->assertActionUsesFormRequest(StoreUserAction::class, StoreUserRequest::class);
    }

    /** @test */
    public function form_request_rules_are_correct(): void
    {
        $request = new StoreUserRequest();
        $this->assertEquals(
            [
                'name'     => 'required|string',
                'email'    => 'required|string',
                'password' => 'required|string',
                'roles'    => 'required|array'

            ],
            $request->rules()
        );
    }

    /** @test */
    public function form_request_authorization_is_true(): void
    {
        /** @var StoreUserRequest $request */
        $request = $this->partialMock(
            StoreUserRequest::class,
            fn (MockInterface $m) => $m->shouldReceive('user')->andReturn(1)
        );
        Gate::shouldReceive('check')->with('store-user', 1)->andReturn(true);
        $this->assertTrue($request->authorize());
    }


}

