<?php

namespace App\Exceptions\Auth;

class InvalidAuthTokenException extends \Exception
{
    protected $message = 'Invalid authorization token.';
    protected $code = 401;
}
