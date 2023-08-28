<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class register extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'userName'=>'required|regex:/^[a-zA-Z0-9\s]+$/|unique:users,name',
            'email'=>'required|email|unique:users,email',
            'password' => ['required', 'string', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/'],
            'profile_picture'=>'required|image'
        ];
    }
    public function messages()
    {
        return [
            'userName.required'=>'the name field is required',
            'userName.regex'=>'userName can contain english letters and numbers only',

            'userName.unique'=>'userName Already used',
            'email.email'=>'the email must be a valid email',
            'email.unique'=>'this email is already used',
            'password.min'=>'the password must be at least 8 chars',
            'password.regex'=>'the password must contain at least one uppercase char,one lowercase chart and a number',
            'password.confirmed'=>'the password conformation must match the password'
        ];
    }
}
