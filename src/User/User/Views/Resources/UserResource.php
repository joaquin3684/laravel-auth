<?php

namespace Hitocean\LaravelAuth\User\User\Views\Resources;

use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\BelongsToManyMultiSelect;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Hitocean\LaravelAuth\User\User\Models\User;
use STS\FilamentImpersonate\Impersonate;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $label = 'Usuario';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(
                Card::make()
                    ->schema([
                                 TextInput::make('name')
                                          ->label("Nombre")
                                          ->required(),
                                 TextInput::make('password')
                                          ->label("Contraseña")
                                          ->required()
                                          ->password()
                                          ->rules(['confirmed']),
                                 TextInput::make('password_confirmation')
                                          ->label("Repetir contraseña")
                                          ->required()
                                          ->password(),
                                 TextInput::make('email')
                                          ->required()
                                          ->email()
                                          ->unique('users', 'email', fn (?User $record): ?User => $record),
                                 Forms\Components\MultiSelect::make('roles')
                                     ->options(Role::where('name', '<>', 'super_admin')->pluck('name', 'id'))
                                                         ->required()
                             ])
            );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                          Tables\Columns\TextColumn::make('name')->label("nombre")->searchable(),
                          Tables\Columns\TextColumn::make('email'),
                          Tables\Columns\TagsColumn::make('roles.name'),
                      ])
            ->filters([
                          Filter::make('roles')
                                ->form([
                                           Forms\Components\Select::make('rol')
                                                                  ->options(Role::where('name', '<>', 'super_admin')->pluck('name', 'id'))
                                       ])
                                ->query(function (Builder $query, array $data): Builder {
                                    return $query
                                        ->when(
                                            $data['rol'],
                                            fn (Builder $query, $rol): Builder => $query->whereHas('roles', fn($q) => $q->where('id', $rol)),
                                        );
                                })
                      ])
            ->prependActions([
                                 Impersonate::make('impersonate')->color('danger'), // <---
                                 Tables\Actions\IconButtonAction::make('cambiar_contraseña')
                                                                ->icon("heroicon-o-key")
                                                                ->color('warning')
                                                                ->action(function (User $record, array $data): void
                                                                {
                                                                    $record->update([
                                                                                        'password' => Hash::make(
                                                                                            $data['password']
                                                                                        )
                                                                                    ]);
                                                                    Filament::notify(
                                                                        'success',
                                                                        'La contraseña fue modificada con exito'
                                                                    );

                                                                })
                                                                ->form([
                                                                           Forms\Components\TextInput::make('password')
                                                                                                     ->label(
                                                                                                         "Contraseña"
                                                                                                     )
                                                                                                     ->required()
                                                                                                     ->password()
                                                                                                     ->rules(
                                                                                                         ['confirmed']
                                                                                                     ),
                                                                           Forms\Components\TextInput::make(
                                                                               'password_confirmation'
                                                                           )
                                                                                                     ->label(
                                                                                                         "Repetir contraseña"
                                                                                                     )
                                                                                                     ->required()
                                                                                                     ->password()
                                                                       ]),
                             ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => \Hitocean\LaravelAuth\User\User\Views\Pages\ListUsers::route('/'),
            'create' => \Hitocean\LaravelAuth\User\User\Views\Pages\CreateUser::route('/create'),
            'edit' => \Hitocean\LaravelAuth\User\User\Views\Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if($user = auth()->user())
        {
            if($user->hasRole('super_admin'))
                return $query;
            else
                return $query->whereDoesntHave('roles', fn($q) => $q->where('name', 'super_admin'));
        }
        return $query;
    }

}
