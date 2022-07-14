<?php


namespace Hitocean\LaravelAuth\User\User\Actions\DTOS;


use Spatie\DataTransferObject\DataTransferObject;

class UpdateUserDTO extends DataTransferObject
{
    public string $name;
	public string $email;
	public int $id;
    public array $roles;
}
