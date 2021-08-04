<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Validations\StoreUserValidator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UsersController extends BaseController
{
    public function store(Request $request, Response $response)
    {
        $validator = new StoreUserValidator((array) $request->getParsedBody());

        if ($validator->fails()) {
            return $this->json($response, [
                'errors' => $validator->errors()->toArray(),
            ], 400);
        }

        $user = User::create($validator->getValidData());

        return $this->json($response, [
            'user' => UserResource::toArray($user),
        ], 201);
    }
}
