<?php


namespace Hitocean\LaravelAuth\Auth\Actions;



use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Lorisleiva\Actions\Concerns\AsAction;
use Hitocean\LaravelAuth\Auth\Actions\DTOS\RegisterUserDTO;
use Hitocean\LaravelAuth\Auth\Notifications\ApiVerifyEmail;
use Hitocean\LaravelAuth\User\Role\Enums\Roles;
use Hitocean\LaravelAuth\Auth\FormRequests\RegisterUserFormRequest;
use Hitocean\LaravelAuth\User\User\Models\User;
use Hitocean\LaravelAuth\User\User\Resources\UserResource;

class RegisterUserAction
{
    use AsAction;

    public function handle(RegisterUserDTO $dto): User
    {
        $user = User::create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => Hash::make($dto->password)
        ]);
        $user->assignRole([Roles::SUPER_ADMIN]);

        $user->notify(new ApiVerifyEmail());

        return $user;
    }

    /**
     * @param RegisterUserFormRequest $request
     * @return UserResource
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     * @throws \Throwable
     * @apiResource Hitocean\LaravelAuth\User\User\Resources\UserResource
     * @apiResourceModel Hitocean\LaravelAuth\User\User\Models\User with=roles
     * @group Authentication
     */
    public function asController(RegisterUserFormRequest $request): UserResource
    {
        $dto = new RegisterUserDTO($request->all());
        $user =  DB::transaction(fn() => $this->handle($dto));
        return new UserResource($user);
    }


}
