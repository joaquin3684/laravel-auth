<?php

namespace Hitocean\LaravelAuth;

use Hitocean\LaravelAuth\Auth\Actions\CreateTokenFromCredentialsAction;
use Hitocean\LaravelAuth\Auth\Actions\ForgotPasswordAction;
use Hitocean\LaravelAuth\Auth\Actions\RegisterUserAction;
use Hitocean\LaravelAuth\Auth\Actions\ResendVerificationEmailAction;
use Hitocean\LaravelAuth\Auth\Actions\ResetPasswordAction;
use Hitocean\LaravelAuth\Auth\Actions\VerifyUserAction;
use Hitocean\LaravelAuth\Commands\LaravelAuthCommand;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelAuthServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-auth')
            ->hasConfigFile('auth')
            ->hasConfigFile('permission')
            ->hasViews()
            ->hasMigration('create_laravel-auth_table')
            ->hasCommand(LaravelAuthCommand::class);

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Hitocean\\LaravelAuth\\Database\\Factories\\'.Str::after($modelName, "Models\\").'Factory'
        );
    }

    public function packageRegistered()
    {
        Route::prefix('api')->group(function () {
            Route::post('login', [CreateTokenFromCredentialsAction::class, 'asController']);
            Route::post('register', [RegisterUserAction::class, 'asController']);
            Route::post('verify/resend', [ResendVerificationEmailAction::class, 'asController'])->name('verification.resend');
            Route::post('forgot-password', [ForgotPasswordAction::class, 'asController'])->name('password.email');
            Route::post('reset-password', [ResetPasswordAction::class, 'asController'])->name('password.reset');
        });

        Route::get('/email/verify/{id}/{hash}', [VerifyUserAction::class, 'asController'])
             ->middleware('signed')
             ->name('verification.verify_api');
    }
}
