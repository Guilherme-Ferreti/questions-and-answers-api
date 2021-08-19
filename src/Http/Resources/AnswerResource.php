<?php

namespace App\Http\Resources;

use App\Collections\AnswerCollection;
use App\Models\Answer;

class AnswerResource
{
    public static function toArray(Answer $answer)
    {
        $attributes = [
            'id' => $answer->id,
            'body' => $answer->body,
            'question_id' => $answer->question_id,
            'user_id' => $answer->user_id,
            'created' => $answer->created,
            'updated' => $answer->updated,
        ];

        if ($answer->user) {
            $attributes['user'] = UserResource::toArray($answer->user);
        }

        return $attributes;
    }

    public static function collection(AnswerCollection $answers) 
    {
        $array = [];

        foreach ($answers as $answer) {
            $array[] = self::toArray($answer);
        }

        return $array;
    } 
}
