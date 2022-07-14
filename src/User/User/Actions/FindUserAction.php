<?php


namespace Hitocean\LaravelAuth\User\User\Actions;


use Lorisleiva\Actions\Concerns\AsAction;
use Hitocean\LaravelAuth\User\User\Models\User;
use Hitocean\LaravelAuth\User\User\Requests\FindUserRequest;
use Hitocean\LaravelAuth\User\User\Resources\UserResource;

class FindUserAction {
    use AsAction;

    /**
     * @group User
     * @authenticated
     * @apiResource Hitocean\LaravelAuth\User\User\Resources\UserResource
     * @apiResourceModel Hitocean\LaravelAuth\User\User\Models\User
     */
    public function asController(FindUserRequest $request, int $id)
    {
        $user_id = $id === 'me' ? $request->user()->user->id : $id;
        return new UserResource($this->handle($user_id));
    }

    public function handle(int $id): User
    {
        return User::findOrFail($id);
    }


}
