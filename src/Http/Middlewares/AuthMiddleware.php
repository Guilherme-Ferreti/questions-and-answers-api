<?php

namespace App\Http\Middlewares;

use Slim\Psr7\Response;
use App\Exceptions\Auth\InvalidAuthTokenException;
use App\Services\AuthService;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class AuthMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $auth_token = $request->getHeader('Authorization')[0] ?? null;

        if (! $auth_token || ! $user = AuthService::getUserFromToken($auth_token)) {
            throw new InvalidAuthTokenException();
        }

        AuthService::user($user);

        return $handler->handle($request);
    }
}
