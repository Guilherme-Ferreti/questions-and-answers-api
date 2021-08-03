<?php

use App\Http\Controllers\QuestionsController;
use App\Http\Middlewares\CorsMiddleware;
use Slim\Routing\RouteCollectorProxy;

$app->group('/api', function (RouteCollectorProxy $route) {
    $route->group('/questions', function (RouteCollectorProxy $route) {
        $route->get('/{id}', [QuestionsController::class, 'show']);

        $route->post('[/]', [QuestionsController::class, 'store']);
    });
})->add(new CorsMiddleware());
