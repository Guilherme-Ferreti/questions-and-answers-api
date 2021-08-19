<?php

namespace App\Validations;

class StoreQuestionAnswerValidator extends BaseValidator
{
    public function rules(): array
    {
        return [
            'body' => 'required|max:65000',
        ];
    }
}
