<?php

namespace App\Exceptions;

use App\Exceptions\Auth\InvalidAuthTokenException;

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
