<?php

use App\Http\Controllers\Auth;
use App\Http\Controllers\{
    QuestionsController,
    QuestionAnswersController,
};
use App\Http\Middlewares\{
    AuthMiddleware, 
    CorsMiddleware,
};

$app->group('/api', function ($route) {
    $route->group('/questions', function ($route) {
        $route->get('[/]', [QuestionsController::class, 'index']);
        $route->get('/{id}', [QuestionsController::class, 'show']);

        $route->group('/{id}/answers', function ($route) {
            $route->get('[/]', [QuestionAnswersController::class, 'index']);
        });
    });

    $route->group('/auth', function ($route) {
        $route->post('/register', [Auth\AuthController::class, 'register']);
        $route->post('/login', [Auth\AuthController::class, 'login']);
        $route->post('/refresh-token', [Auth\AuthController::class, 'refresh_token']);

        $route->group('', function ($route) {
            $route->group('/questions', function ($route) {
                $route->post('[/]', [Auth\QuestionsController::class, 'store']);
                $route->put('/{id}', [Auth\QuestionsController::class, 'update']);
                $route->delete('/{id}', [Auth\QuestionsController::class, 'destroy']);

                $route->group('/{id}/answers', function ($route) {
                    $route->post('[/]', [Auth\QuestionAnswersController::class, 'store']);
                });
            });
        })->add(new AuthMiddleware());
    });
})->add(new CorsMiddleware());
