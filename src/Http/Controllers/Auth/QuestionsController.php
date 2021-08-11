<?php

namespace App\Controllers\Auth;

use App\Models\Question;
use App\Http\Controllers\BaseController;
use App\Http\Resources\QuestionResource;
use Slim\Exception\HttpNotFoundException;
use App\Validations\StoreQuestionsValidator;
use App\Validations\UpdateQuestionsValidator;
use Psr\Http\Message\ServerRequestInterface as Request;

class QuestionsController extends BaseController
{
    public function store(Request $request, StoreQuestionsValidator $v)
    {
        $attributes = $v->validate((array) $request->getParsedBody());

        $question = Question::create($attributes);

        return $this->json(QuestionResource::toArray($question), 201);
    }

    public function update(Request $request, $id, UpdateQuestionsValidator $v)
    {
        if (! $question = Question::findById($id)) {
            throw new HttpNotFoundException($request);
        }

        $attributes = $v->validate((array) $request->getParsedBody());

        $question->setAttributes($attributes)->update();

        return $this->json(QuestionResource::toArray($question));
    }

    public function destroy(Request $request, $id)
    {
        if (! $question = Question::findById($id)) {
            throw new HttpNotFoundException($request);
        }

        $question->delete();

        return $this->json([], 204);
    }
}
