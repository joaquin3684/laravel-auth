<?php

namespace Hitocean\LaravelAuth\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Hitocean\LaravelAuth\LaravelAuth
 */
class LaravelAuth extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-auth';
    }
}
