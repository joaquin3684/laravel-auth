<?php

namespace Hitocean\LaravelAuth\User\Role\Enums;

class Roles
{
    public const SUPER_ADMIN = 'super_admin';
    public const ADMIN = 'admin';

    public static function all()
    {
        return [
            self::SUPER_ADMIN,
            self::ADMIN,

        ];
    }
}
