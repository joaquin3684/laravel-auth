<?php


namespace Hitocean\LaravelAuth\Auth\FormRequests;


use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

class CreateTokenFromCredentialsFormRequest extends FormRequest
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
     *  * @bodyParam user_id int required The id of the user. Example: 9

     */
    #[ArrayShape(['password' => "string", 'email' => "string"])]
    public function rules() : array
    {
        return [
            'password' => 'required|string',
            'email' => 'required|string|email',
        ];
    }
}
