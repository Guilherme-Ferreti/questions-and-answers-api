<?php

namespace App\Validations;

class LoginValidator extends BaseValidator
{
    public function rules(): array
    {
        return [
            'email' => 'required|email|max:255|exists:users,email',
            'password' => 'required|max:255',
        ];
    }

    public function aliases(): array
    {
        return [
            'email' => 'e-mail address',
        ];
    }
}
