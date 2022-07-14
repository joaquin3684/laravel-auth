<?php

namespace Hitocean\LaravelAuth\User\Role\Views\Pages;

use Filament\Pages\Actions\ButtonAction;
use Filament\Resources\Pages\ListRecords;
use Hitocean\LaravelAuth\User\Role\Views\Resources\RoleResource;

class ListRoles extends ListRecords
{
    protected static string $resource = RoleResource::class;

    protected function getActions(): array
    {
        return array_merge([
            ButtonAction::make('setting')
                ->label(__('filament-shield::filament-shield.page.name'))
                ->url(static::$resource::getUrl('settings'))
                ->icon(__('filament-shield::filament-shield.page.icon'))
                ->color('success')
        ], parent::getActions());
    }
}
