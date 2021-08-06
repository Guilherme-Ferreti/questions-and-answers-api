<?php

namespace App\Validations;

class StoreQuestionsValidator extends BaseValidator
{
    public function rules(): array
    {
        return [
            'title' => 'required|max:255',
            'body' => 'required|max:65000',
            'user_id' => 'required|numeric|exists:users,id'
        ];
    }
}
