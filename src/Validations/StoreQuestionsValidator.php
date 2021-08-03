<?php

namespace App\Validations;

class StoreQuestionsValidator extends BaseValidator
{
    public function rules(): array
    {
        return [
            'body' => 'required|max:65000',
            'user_id' => 'required|numeric|exists:users,id'
        ];
    }
}
