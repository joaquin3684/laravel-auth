<?php


namespace Hitocean\LaravelAuth\Auth\Actions\DTOS;


use Spatie\DataTransferObject\DataTransferObject;

class RegisterUserDTO extends DataTransferObject
{
    public string $name;
    public string $password;
    public string $email;
}
