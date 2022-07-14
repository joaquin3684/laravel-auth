<?php


namespace Hitocean\LaravelAuth\Auth\Actions;


use Illuminate\Support\Facades\Password;
use Lorisleiva\Actions\Concerns\AsAction;
use Hitocean\LaravelAuth\Auth\FormRequests\ForgotPasswordFormRequest;

class ForgotPasswordAction
{
    use AsAction;

    public function handle($email)
    {
        $status = Password::sendResetLink(['email' => $email]);

        return $status === Password::RESET_LINK_SENT;
    }

    /**
     * @param ForgotPasswordFormRequest $request
     * @group Authentication
     */
    public function asController(ForgotPasswordFormRequest $request)
    {
        $this->handle($request->get('email'));
    }
}
