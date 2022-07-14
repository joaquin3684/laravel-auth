<?php


namespace Hitocean\LaravelAuth\User\User\Actions;

use DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Hitocean\LaravelAuth\User\User\Actions\DTOS\StoreUserDTO;
use Hitocean\LaravelAuth\User\User\Models\User;
use Hitocean\LaravelAuth\User\User\Requests\StoreUserRequest;
use Hitocean\LaravelAuth\User\User\Resources\UserResource;

class StoreUserAction
{
    use AsAction;

    /**
     * @group            User
     * @authenticated
     * @apiResourceModel Hitocean\LaravelAuth\User\User\Models\User
     * @apiResource      Hitocean\LaravelAuth\User\User\Resources\UserResource
     * @param StoreUserRequest $request
     * @return UserResource
     */
    public function asController(StoreUserRequest $request): UserResource
    {
        $dto          = new StoreUserDTO($request->all());
        $user = DB::transaction(fn () => $this->handle($dto));
        return new UserResource($user);
    }

    public function handle(StoreUserDTO $dto): User
    {
        $user = User::create(
            [
                'name' => $dto->name,
                'email' => $dto->email,
                'password' => $dto->password,
            ]
        );

        $user->assignRole($dto->roles);
        return $user;
    }


}
