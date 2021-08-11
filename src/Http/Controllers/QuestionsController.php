<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Http\Resources\QuestionResource;
use Slim\Exception\HttpNotFoundException;
use Psr\Http\Message\ServerRequestInterface as Request;

class QuestionsController extends BaseController
{
    public function index()
    {
        $questions = Question::all()->load('topics', 'user');

        return $this->json(QuestionResource::collection($questions));
    }

    public function show(Request $request, $id)
    {
        if (! $question = Question::findById($id)) {
            throw new HttpNotFoundException($request);
        }

        $question->load('topics', 'user');

        return $this->json(QuestionResource::toArray($question));
    }
}
