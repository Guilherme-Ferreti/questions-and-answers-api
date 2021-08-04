<?php

use App\Http\Controllers\QuestionsController;
use App\Http\Controllers\UsersController;
use App\Http\Middlewares\CorsMiddleware;
use Slim\Routing\RouteCollectorProxy;

$app->group('/api', function (RouteCollectorProxy $route) {
    $route->group('/questions', function (RouteCollectorProxy $route) {
        $route->get('[/]', [QuestionsController::class, 'index']);
        $route->get('/{id}', [QuestionsController::class, 'show']);
        $route->post('[/]', [QuestionsController::class, 'store']);
        $route->put('/{id}', [QuestionsController::class, 'update']);
        $route->delete('/{id}', [QuestionsController::class, 'destroy']);
    });

    $route->group('/users', function (RouteCollectorProxy $route) {
        $route->post('[/]', [UsersController::class, 'store']);
    });
})->add(new CorsMiddleware());
