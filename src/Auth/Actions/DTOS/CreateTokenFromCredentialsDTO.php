<?php

namespace Hitocean\LaravelAuth\Auth\Actions\DTOS;

use Spatie\DataTransferObject\DataTransferObject;

class CreateTokenFromCredentialsDTO extends DataTransferObject
{
    public string $email;
    public string $password;
}
