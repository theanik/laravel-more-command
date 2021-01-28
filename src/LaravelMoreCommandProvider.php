<?php

namespace Theanik\LaravelMoreCommand;

use Illuminate\Support\ServiceProvider;
use Theanik\LaravelMoreCommand\Commands\CreateRepositoryCommand;
use Theanik\LaravelMoreCommand\Commands\CreateTraitCommand;
use Theanik\LaravelMoreCommand\Commands\CreateServiceCommand;

use Theanik\LaravelMoreCommand\Commands\CreateModuleRepositoryCommand;
use Theanik\LaravelMoreCommand\Commands\CreateModuleTraitCommand;
use Theanik\LaravelMoreCommand\Commands\CreateModuleServiceCommand;


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
            
            
            // For nWidart/laravel-modules:
            CreateModuleRepositoryCommand::class,
            CreateModuleTraitCommand::class,
            CreateModuleServiceCommand::class
        ]);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
