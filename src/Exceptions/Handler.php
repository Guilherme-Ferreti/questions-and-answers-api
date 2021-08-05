<?php

namespace App\Exceptions;

class Handler extends ExceptionHandler
{
    protected array $dontReport = [
        InvalidAuthTokenException::class,
    ];

    protected function register(): void
    {
        //
    }
}
