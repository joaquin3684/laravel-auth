<?php


namespace Hitocean\LaravelAuth\User\User\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    public function toArray($request)
    {
        return [
           $this->name
        ];
    }
}
