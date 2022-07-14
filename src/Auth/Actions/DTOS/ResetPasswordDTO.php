<?php

namespace Hitocean\LaravelAuth\Auth\Actions\DTOS;

use Spatie\DataTransferObject\DataTransferObject;

class ResetPasswordDTO extends DataTransferObject
{
    public string $token;
    public string $email;
    public string $password;
    public string $password_confirmation;
}
