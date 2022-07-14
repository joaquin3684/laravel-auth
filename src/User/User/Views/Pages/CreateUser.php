<?php

namespace Hitocean\LaravelAuth\User\User\Views\Pages;

use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Hitocean\LaravelAuth\User\User\Models\User;
use Hitocean\LaravelAuth\User\User\Views\Resources\UserResource;
use function filled;
use function now;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    public function create(bool $another = false): void
    {

        DB::transaction(function() use($another)
        {

            $data = $this->form->getState();
            \Log::info("data", $data);
            $user = User::create([
                                     'name'     => $data['name'],
                                     'email'    => $data['email'],
                                     'password' => Hash::make($data['password'])
                                 ]);
            $user->assignRole($data['roles']);
            $user->email_verified_at = now();
            $user->save();

            $this->record = $user;


            if (filled($this->getCreatedNotificationMessage())) {
                $this->notify(
                                     'success',
                                     $this->getCreatedNotificationMessage(),
                    isAfterRedirect: ! $another,
                );
            }

            if ($another) {
                // Ensure that the form record is anonymized so that relationships aren't loaded.
                $this->form->model($this->record::class);
                $this->record = null;

                $this->fillForm();

                return;
            }

            $this->redirect($this->getRedirectUrl());
        });
    }
}
