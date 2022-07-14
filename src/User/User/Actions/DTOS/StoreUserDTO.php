<?php


namespace Hitocean\LaravelAuth\User\User\Actions\DTOS;


use Spatie\DataTransferObject\DataTransferObject;

class StoreUserDTO extends DataTransferObject
{
    public string $name;
	public string $email;
	public string $password;
    public array $roles;
}
