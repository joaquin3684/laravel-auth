<?php

namespace Hitocean\LaravelAuth\Tests;

use Hitocean\LaravelAuth\LaravelAuthServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\Permission\PermissionServiceProvider;

class TestCase extends Orchestra
{
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Hitocean\\LaravelAuth\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelAuthServiceProvider::class,
            PermissionServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        Schema::dropAllTables();

        $a = config('permission.column_names');
        $migration = include __DIR__.'/../database/migrations/create_users_table.php.stub';
        $migration->up();

        $migration3 = include __DIR__.'/../database/migrations/create_password_resets_table.php.stub';
        $migration3->up();

        $migration2 = include __DIR__ . '/../database/migrations/create_permission_tables.php.stub';
        $migration2->up();
    }
}
