<?php


namespace Hitocean\LaravelAuth\User\Role\Enums;


class Roles
{
    const SUPER_ADMIN = 'super_admin';
    const ADMIN = 'admin';


    public static function all()
    {
        return [
            self::SUPER_ADMIN,
            self::ADMIN,

        ];
    }


}
