<?php

namespace Hitocean\LaravelAuth\User\User\Views\Pages;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Pages\EditRecord;
use Spatie\Permission\Models\Role;
use Hitocean\LaravelAuth\User\User\Models\User;
use Hitocean\LaravelAuth\User\User\Views\Resources\UserResource;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema(
                Forms\Components\Card::make()->schema([
                                                          Forms\Components\TextInput::make('name')
                                                                                    ->label("Nombre")
                                                                                    ->required(),
                                                          Forms\Components\TextInput::make('email')
                                                                                    ->required()
                                                                                    ->email()
                                                                                    ->unique('users', 'email',  fn (?User $record): ?User => $record),
                                                          Forms\Components\BelongsToManyMultiSelect::make('roles')
                                                                                                   ->relationship('roles', 'name')
                                                                                                   ->options(
                                                                                                       Role::all()->pluck(
                                                                                                           'name',
                                                                                                           'id'
                                                                                                       )
                                                                                                   )
                                                                                                   ->required()
                                                      ])
            );
    }
}
