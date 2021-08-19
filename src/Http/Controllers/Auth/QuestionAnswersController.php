<?php

namespace App\Http\Controllers\Auth;

use App\Models\Answer;
use App\Models\Question;
use App\Http\Controllers\BaseController;
use Slim\Exception\HttpNotFoundException;
use App\Validations\StoreQuestionAnswerValidator;
use Psr\Http\Message\ServerRequestInterface as Request;

class QuestionAnswersController extends BaseController
{
    public function store(Request $request, $id, StoreQuestionAnswerValidator $v)
    {
        if (! $question = Question::findById($id)) {
            throw new HttpNotFoundException($request);
        }

        $attributes = $v->validate((array) $request->getParsedBody());

        $attributes['user_id'] = auth_user()->id;
        $attributes['question_id'] = $question->id;

        $answer = (new Answer($attributes))->save()->refresh()->toArray();

        return $this->json($answer, 201);
    }
}
