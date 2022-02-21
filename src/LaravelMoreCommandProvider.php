<?php

namespace Theanik\LaravelMoreCommand;

use Illuminate\Support\ServiceProvider;
use Theanik\LaravelMoreCommand\Commands\CreateRepositoryCommand;
use Theanik\LaravelMoreCommand\Commands\CreateTraitCommand;
use Theanik\LaravelMoreCommand\Commands\CreateServiceCommand;
use Theanik\LaravelMoreCommand\Commands\CreateBladeCommand;
use Theanik\LaravelMoreCommand\Commands\ClearLogCommand;

use Theanik\LaravelMoreCommand\Commands\CreateModuleRepositoryCommand;
use Theanik\LaravelMoreCommand\Commands\CreateModuleTraitCommand;
use Theanik\LaravelMoreCommand\Commands\CreateModuleServiceCommand;
use Theanik\LaravelMoreCommand\Commands\CreateModuleBladeCommand;



class LaravelMoreCommandProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            CreateRepositoryCommand::class,
            CreateTraitCommand::class,
            CreateServiceCommand::class,
            CreateBladeCommand::class,
            ClearLogCommand::class,

            // For nWidart/laravel-modules:
            CreateModuleRepositoryCommand::class,
            CreateModuleTraitCommand::class,
            CreateModuleServiceCommand::class,
            CreateModuleBladeCommand::class
        ]);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/laravel-more-command.php' => config_path('laravel-more-command.php'),
        ], 'config');
    }
}
