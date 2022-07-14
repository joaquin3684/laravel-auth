<?php

namespace Hitocean\LaravelAuth\User\User\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::check('change-password', $this->user());
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array
     *
     */
    public function rules(): array
    {
        return [
            'password' => 'required|string',

        ];
    }
}
