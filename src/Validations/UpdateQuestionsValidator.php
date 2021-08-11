<?php

namespace App\Validations;

class UpdateQuestionsValidator extends BaseValidator
{
    public function rules(): array
    {
        return [
            'title' => 'required|max:255',
            'body' => 'required|max:65000',
        ];
    }
}
