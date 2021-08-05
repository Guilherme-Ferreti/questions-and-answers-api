<?php

namespace App\Http\Middlewares;

use Slim\Psr7\Response;
use App\Exceptions\Auth\InvalidAuthTokenException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class AuthMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $auth_token = $request->getHeader('Authorization')[0] ?? null;

        if (! $auth_token) {
            throw new InvalidAuthTokenException();
        }

        // Get user from auth token

        return $handler->handle($request);
    }
}
