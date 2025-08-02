<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    public function rules()
    {
        return [
            'username' => 'required|unique:users',
            'email' => 'required|unique:users|email',
            'password' => 'required|min:5|confirmed',
        ];
    }

    public function authorize()
    {
        return true;
    }

}
