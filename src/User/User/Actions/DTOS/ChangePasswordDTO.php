<?php


namespace Hitocean\LaravelAuth\User\User\Actions\DTOS;


use Spatie\DataTransferObject\DataTransferObject;

class ChangePasswordDTO extends DataTransferObject
{
    public string $password;
    public int $id;
}
