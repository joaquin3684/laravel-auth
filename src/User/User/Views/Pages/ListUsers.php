<?php

namespace Hitocean\LaravelAuth\User\User\Views\Pages;

use Filament\Resources\Pages\ListRecords;
use Hitocean\LaravelAuth\User\User\Views\Resources\UserResource;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;
}
