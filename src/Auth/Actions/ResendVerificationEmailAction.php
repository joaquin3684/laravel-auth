<?php

namespace Hitocean\LaravelAuth\Auth\Actions;

use Hitocean\LaravelAuth\User\User\Models\User;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class ResendVerificationEmailAction
{
    use AsAction;

    public function handle($email)
    {
        $user = User::whereEmail($email)->first();
        if ($user && ! $user->email_verified_at) {
            $user->sendEmailVerificationNotification();
        }
    }

    /**
     * @param Request $request
     * @bodyParam email string required example: pepe@gmail.com
     * @group Authentication
     */
    public function asController(Request $request)
    {
        $this->handle($request->get('email'));
    }
}
