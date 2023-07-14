<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeProjectsRequest extends FormRequest
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
            "employee_id" => "required",
            "name" => "required",
            "project" => "required",
            "startDate" => "required",
            "endDate" => "required",
            'document' => 'required|array',
            'document.*' => 'required|file|max:1000',
        ];
    }


    public function messages()
    {
        return [
            'document.required' => 'Documentation field is required.',
            'document.*.required' => 'At least one file is required.',
            'document.*.max' => 'The files should not be exceeded more than 1 MB.',
        ];
    }
}
