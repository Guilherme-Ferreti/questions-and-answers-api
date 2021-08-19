<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Http\Resources\AnswerResource;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

class QuestionAnswersController extends BaseController
{
    public function index(Request $request, $id)
    {
        if (! $question = Question::findById($id)) {
            throw new HttpNotFoundException($request);
        }

        $question->loadAnswers()->answers->loadUsers();

        return $this->json(AnswerResource::collection($question->answers));
    }
}
