<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function rules()
    {
        $userId = $this->route()->parameter('user');

        return [
            'username' => "required|unique:users,username,{$userId}",
            'email' => "required|email|unique:users,email,{$userId}",
            'password' => 'confirmed',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
