<?php


namespace Hitocean\LaravelAuth\Auth\Actions;


use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Lorisleiva\Actions\Concerns\AsAction;
use Hitocean\LaravelAuth\Auth\Actions\DTOS\CreateTokenFromCredentialsDTO;
use Hitocean\LaravelAuth\Auth\Exceptions\EmailVerificationException;
use Hitocean\LaravelAuth\Auth\FormRequests\CreateTokenFromCredentialsFormRequest;
use Hitocean\LaravelAuth\User\User\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class CreateTokenFromCredentialsAction
{
    use AsAction;

    public function handle(CreateTokenFromCredentialsDTO $dto): string
    {
        $user = User::where('email', $dto->email)->first();

        if (!$user || !Hash::check($dto->password, $user->password)) {
            throw new ModelNotFoundException("El usuario o contraseña son incorrectos");
        }

        if(is_null($user->email_verified_at))
            throw new EmailVerificationException;

        return JWTAuth::fromUser($user);
    }

    /**
     * @param CreateTokenFromCredentialsFormRequest $request
     * @return JsonResponse
     * @throws EmailVerificationException
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     * @response {
     *      token: string
     * }
     * @group Authentication
     */
    public function asController(CreateTokenFromCredentialsFormRequest $request): JsonResponse
    {
        $dto = new CreateTokenFromCredentialsDTO($request->all());
        $token = $this->handle($dto);
        return response()->json(['token' => $token], 200);
    }
}
