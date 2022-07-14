<?php


namespace Hitocean\LaravelAuth\Auth\FormRequests;


use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @bodyParam password_confirmation string required
     */
    public function rules(): array
    {
        return [
            'token' => 'required',
            'password_confirmation' => 'required|string',
            'password' => 'required|confirmed',
            'email' => 'required|email',
        ];
    }

}
