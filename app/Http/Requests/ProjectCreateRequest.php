<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectCreateRequest extends FormRequest
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
            
            "project" => [
                'required',
                'regex:/^[A-Za-z\s]+$/',
                'min:3',
                'string',
                Rule::unique('projects')->ignore($this->id),
            ]
            
        ];
    }
}
