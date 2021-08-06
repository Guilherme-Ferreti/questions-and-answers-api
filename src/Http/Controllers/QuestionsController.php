<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Slim\Exception\HttpNotFoundException;
use App\Validations\StoreQuestionsValidator;
use App\Validations\UpdateQuestionsValidator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class QuestionsController extends BaseController
{
    public function index(Response $response)
    {
        $questions = Question::all();

        return $this->json($response, [
            'questions' => array_map(fn($question) => $question->toArray(), $questions),
        ]);
    }

    public function show(Request $request, Response $response, $id)
    {
        if (! $question = Question::findById($id)) {
            throw new HttpNotFoundException($request);
        }

        return $this->json($response, [
            'question' => $question->toArray(),
        ]);
    }

    public function store(Request $request, Response $response, StoreQuestionsValidator $v)
    {
        $attributes = $v->validate((array) $request->getParsedBody());

        $question = Question::create($attributes);

        return $this->json($response, [
            'question' => $question->toArray(),
        ], 201);
    }

    public function update(Request $request, Response $response, $id, UpdateQuestionsValidator $v)
    {
        if (! $question = Question::findById($id)) {
            throw new HttpNotFoundException($request);
        }

        $attributes = $v->validate((array) $request->getParsedBody());

        $question->setAttributes($attributes)->update();

        return $this->json($response, [
            'question' => $question->toArray(),
        ]);
    }

    public function destroy(Request $request, Response $response, $id)
    {
        if (! $question = Question::findById($id)) {
            throw new HttpNotFoundException($request);
        }

        $question->delete();

        return $this->json($response, [], 204);
    }
}
