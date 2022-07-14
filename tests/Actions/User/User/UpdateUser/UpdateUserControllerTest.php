<?php

namespace Hitocean\LaravelAuth\Tests\Actions\User\User\UpdateUser;

use Hitocean\LaravelAuth\User\Role\Enums\Roles;
use Hitocean\LaravelAuth\User\User\Actions\DTOS\UpdateUserDTO;
use Hitocean\LaravelAuth\User\User\Actions\UpdateUserAction;
use Hitocean\LaravelAuth\User\User\Models\User;
use Hitocean\LaravelAuth\User\User\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Gate;
use Mockery\MockInterface;
use Tests\NeedsFormRequests;
use Tests\ResourceHelpers\User\UserResourceHelperTest;
use Tests\TestCase;
use Tests\UserMustBeAuthenticated;
use Tests\UserMustBeVerified;

class UpdateUserControllerTest extends TestCase implements UserMustBeAuthenticated, NeedsFormRequests, UserMustBeVerified
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
    public function update_user($rol): void
    {
        $user = User::factory()->create();

        $data = [
            'id' => $user->id,
            'name' => $this->faker->name,
            'email' => $this->faker->name,
            'roles' => [$this->faker->name],

        ];
        $expectedJson = UserResourceHelperTest::complete($user);

        $this->basicPutAssert(
            $data,
            UpdateUserAction::class,
            UpdateUserDTO::class,
            "api/users/{$user->id}",
            $rol,
            $expectedJson,
            $user
        );
    }

    /** @test */
    public function user_must_be_authenticated_exception(): void
    {
        $this->assert_route_needs_logged_user('put', 'api/users/{id}');
    }

    /** @test */
    public function user_must_be_verified_exception(): void
    {
        $this->assert_route_needs_verified_user('put', 'api/users/{id}');
    }

    /** @test */
    public function unaccepted_roles(): void
    {
        $this->assert_unaccepted_roles('put', 'api/users/{id}');
    }

    /** @test */
    public function controller_uses_correct_form_request(): void
    {
        $this->assertActionUsesFormRequest(UpdateUserAction::class, UpdateUserRequest::class);
    }

    /** @test */
    public function form_request_rules_are_correct(): void
    {
        $request = new UpdateUserRequest();
        $this->assertEquals(
            [
                'name' => 'required|string',
                'email' => 'required|string',
                'roles' => 'required|array',
            ],
            $request->rules()
        );
    }

    /** @test */
    public function form_request_authorization_is_true(): void
    {
        /** @var UpdateUserRequest $request */
        $request = $this->partialMock(
            UpdateUserRequest::class,
            fn (MockInterface $m) => $m->shouldReceive('user')->andReturn(1)
        );
        Gate::shouldReceive('check')->with('update-user', 1)->andReturn(true);
        $this->assertTrue($request->authorize());
    }
}
