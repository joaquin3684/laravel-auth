<?php

namespace Hitocean\LaravelAuth\Auth\Views\Pages;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use JeffGreco13\FilamentBreezy\Http\Livewire\Auth\ResetPassword as FilamentBreezyResetPassword;

class ResetPassword extends FilamentBreezyResetPassword
{
    public function submit()
    {
        $data = $this->form->getState();

        if ($this->isResetting) {
            $response = Password::reset([
                                            'token' => $this->token,
                                            'email' => $this->email,
                                            'password' => $data['password'],
                                        ], function ($user, $password) {
                $user->password = Hash::make($password);
                $user->setRememberToken(Str::random(60));
                $user->save();
                event(new PasswordReset($user));
            });

            if ($response == Password::PASSWORD_RESET) {
                return redirect(route('filament.auth.login', ['email' => $this->email,'reset' => true]));
            } else {
                $this->notify('danger', __("filament-breezy::default.reset_password.notification_error"));
            }
        } else {
            $response = Password::sendResetLink(['email' => $this->email]);
            if ($response == Password::RESET_LINK_SENT) {
                $this->notify('success', __("filament-breezy::default.reset_password.notification_success"));

                $this->hasBeenSent = true;
            } else {
                $this->notify('danger', match ($response) {
                    "passwords.throttled" => __("passwords.throttled"),
                    "passwords.user" => __("passwords.user")
                });
            }
        }
    }

}
