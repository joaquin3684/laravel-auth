<?php


namespace Hitocean\LaravelAuth\User\User\Actions;


use Hash;
use Lorisleiva\Actions\Concerns\AsAction;
use Hitocean\LaravelAuth\User\User\Actions\DTOS\ChangePasswordDTO;
use Hitocean\LaravelAuth\User\User\Models\User;
use Hitocean\LaravelAuth\User\User\Requests\ChangePasswordRequest;

class ChangePasswordAction
{
    use AsAction;

    public function handle(ChangePasswordDTO $dto)
    {
        $user = User::findOrFail($dto->id);
        $user->password = Hash::make($dto->password);
        $user->name = 'carlos';
        $user->save();

    }

    /**
     * @group User
     * @authenticated
     */
    public function asController(ChangePasswordRequest $request, int $id)
    {
        $data = $request->all();
        $dto = new ChangePasswordDTO($data);
        \DB::transaction(fn() => $this->handle($dto));
    }


}
