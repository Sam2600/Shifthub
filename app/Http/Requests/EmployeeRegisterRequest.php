<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class EmployeeRegisterRequest extends FormRequest
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

            "photo" => "required|mimes:jpg,jpeg,png|max:2048",

            "name"  => [
                'required',
                'regex:/^[A-Za-z\s]+$/',
                'min:3',
                'string',
                Rule::unique('employees')->ignore($this->id),
            ],

            "email"  => [
                'required',
                'email',
                'min:13',
                Rule::unique('employees')->ignore($this->id),
            ],

            'phone' => [
                'required',
                'numeric',
                'regex:/^0\d{10}$/',
                Rule::unique('employees')->ignore($this->id),
            ],

            "dateOfBirth"  => [
                'required',
                'date',
                'before_or_equal:' . Carbon::now()->subYears(18)->format('Y-m-d'),
            ],

            "nrc" => [
                'required',
                'regex:/^([0-9]{1,2})\/([A-Z][a-z]|[A-Z][a-z][a-z])([A-Z][a-z]|[A-Z][a-z][a-z])([A-Z][a-z]|[A-Z][a-z][a-z])\([N,P,E]\)[0-9]{6}$/',
                Rule::unique('employees')->ignore($this->id),
            ],

            "gender"  => "required",
            "address"  => "required|min:10",
            "language"  => "required",
            "career"  => "required",
            "level"  => "required",
            "prog_lang"  => "required"
        ];
    }


    public function messages()
    {
        return [

            "dateOfBirth.before_or_equal" => "Age must be minimal 18 years to register",
            "prog_lang.required" => "Please describe your skills",
            "phone.regex" => "Phone number must be start with 0 and minimum length needs to be 11",
        ];
    }
}
