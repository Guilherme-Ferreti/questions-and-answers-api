<?php

namespace App\Validations;

class StoreUserValidator extends BaseValidator
{
    public function rules(): array
    {
        return [
            'username' => 'required|max:60|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|max:255',
        ];
    }
}
