<?php

namespace Hitocean\LaravelAuth\Auth\FormRequests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules(): array
    {
        return [
            'password' => 'required|string|confirmed',
            'email' => 'required|string|email',
            'name' => 'required|string',
            'password_confirmation' => 'required',
        ];
    }
}
