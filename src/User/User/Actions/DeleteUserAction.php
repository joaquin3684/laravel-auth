<?php

namespace Hitocean\LaravelAuth\User\User\Actions;

use Lorisleiva\Actions\Concerns\AsAction;

use Hitocean\LaravelAuth\User\User\Models\User;
use Hitocean\LaravelAuth\User\User\Requests\DeleteUserRequest;

class DeleteUserAction
{
    use AsAction;

    public function handle($id)
    {
        User::destroy($id);
    }

    /**
     * @group User
     * @authenticated
     */
    public function asController(DeleteUserRequest $request, int $id)
    {
        \DB::transaction(fn() => $this->handle($id));
    }


}
