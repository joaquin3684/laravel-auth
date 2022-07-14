<?php

namespace Hitocean\LaravelAuth\User\User\Actions;

use DB;
use Hitocean\LaravelAuth\User\Role\Enums\Roles;
use Hitocean\LaravelAuth\User\User\Actions\DTOS\UpdateUserDTO;
use Hitocean\LaravelAuth\User\User\Models\User;
use Hitocean\LaravelAuth\User\User\Requests\UpdateUserRequest;
use Hitocean\LaravelAuth\User\User\Resources\UserResource;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateUserAction
{
    use AsAction;

    /**
     * @group            User
     * @authenticated
     * @apiResourceModel Hitocean\LaravelAuth\User\User\Models\User
     * @apiResource      Hitocean\LaravelAuth\User\User\Resources\UserResource
     * @param UpdateUserRequest $request
     * @param int $id
     * @return UserResource
     */
    public function asController(UpdateUserRequest $request, int $id): UserResource
    {
        $data = $request->all();
        $data['id'] = $id;
        $dto = new UpdateUserDTO($data);
        $user = DB::transaction(fn () => $this->handle($dto));

        return new UserResource($user);
    }

    public function handle(UpdateUserDTO $dto): User
    {
        $user = User::findOrFail($dto->id);
        $user->update(
            [
                'name' => $dto->name,
                'email' => $dto->email,

            ]
        );
        $user->roles()->detach();
        $user->assignRole(Roles::SUPER_ADMIN);

        return $user;
    }
}
