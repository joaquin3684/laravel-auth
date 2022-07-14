<?php


namespace Hitocean\LaravelAuth\Auth\Exceptions;


class EmailVerificationException extends \Exception
{
    protected $message = "Por favor verifique su email";
    protected $code = 402;
}
