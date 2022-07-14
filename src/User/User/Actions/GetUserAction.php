<?php

namespace Hitocean\LaravelAuth\User\User\Actions;

use Hitocean\LaravelAuth\User\User\Models\User;
use Hitocean\LaravelAuth\User\User\Requests\GetUserRequest;
use Hitocean\LaravelAuth\User\User\Resources\UserResource;
use Lorisleiva\Actions\Concerns\AsAction;

class GetUserAction
{
    use AsAction;

    /**
     * @group User
     * @authenticated
     * @apiResourceModel      Hitocean\LaravelAuth\User\User\Models\User
     * @apiResourceCollection Hitocean\LaravelAuth\User\User\Resources\UserResource
     * @param GetUserRequest $request
     */
    public function asController(GetUserRequest $request)
    {
        return UserResource::collection($this->handle());
    }

    public function handle()
    {
        return User::all();
    }
}
