<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Validations\StoreQuestionsValidator;
use App\Validations\UpdateQuestionsValidator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

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

    public function store(Request $request, Response $response)
    {
        $validator = new StoreQuestionsValidator((array) $request->getParsedBody());

        if ($validator->fails()) {
            return $this->json($response, [
                'errors' => $validator->errors()->firstOfAll(),
            ], 400);
        }

        $question = Question::create($validator->getValidData());

        return $this->json($response, [
            'question' => $question->refresh()->toArray(),
        ], 201);
    }

    public function update(Request $request, Response $response, $id)
    {
        if (! $question = Question::findById($id)) {
            throw new HttpNotFoundException($request);
        }

        $validator = new UpdateQuestionsValidator((array) $request->getParsedBody());

        if ($validator->fails()) {
            return $this->json($response, [
                'errors' => $validator->errors()->firstOfAll(),
            ], 400);
        }

        $question->setAttributes($validator->getValidData())->update();

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
