<?php


namespace Hitocean\LaravelAuth\User\User\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Gate;

class GetRolesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::check('get-roles', $this->user());

    }

    /**
     * Get the validation rules that apply to the request.
     * @return array
     *
     */
    public function rules() : array
    {
        return [

        ];
    }
}
