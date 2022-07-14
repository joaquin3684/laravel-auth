<?php


namespace Hitocean\LaravelAuth\User\User\Actions;


use Lorisleiva\Actions\Concerns\AsAction;

use Hitocean\LaravelAuth\User\Role\Enums\Roles;
use Hitocean\LaravelAuth\User\User\Requests\GetRolesRequest;

class GetRolesAction
{
    use AsAction;

    public function handle()
    {
        return Roles::all();
    }

    /**
     * @group Rol
     * @authenticated
     * @response ['Super admin', 'Administrator']
     */
    public function asController(GetRolesRequest $request)
    {
        return $this->handle();
    }


}
