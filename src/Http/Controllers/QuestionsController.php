<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class QuestionsController extends BaseController
{
    public function store(Request $request, Response $response)
    {
        $question = Question::create((array) $request->getParsedBody());

        return $this->json($response, [
            'question' => $question->refresh()->toArray(),
        ], 201);
    }
}
