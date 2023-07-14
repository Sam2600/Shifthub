<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [

            "id" => "required|integer|min:1",
            "password" => "required"

        ];
    }

    /**
    * Get the error messages for the defined validation rules.
    *
    * @return array
    */

    public function messages()
    {
        return [

            'id.required' => 'Please fill out your id',
            'id.integer' => 'ID field must be digit',
            'password.required' => 'Please fill out your login password'

        ];
    }
}
