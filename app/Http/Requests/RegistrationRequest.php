<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'username.required' => 'This field is required, please use your brain before you click the Sign up button',
            'email.required' => 'Field is required, please use your brain before you click the Sign up button',
            'password.required' => 'Field is required, please use your brain before you click the Sign up button',

            'email.unique' => 'This email is already in use, maybe you have dementia?',
            'username.unique' => 'This username is already in use, try being more creative',
            'password.confirmed' => 'Passwords do not match, maybe you have dementia and forgot your own password????????????',
        ];
    }
}
