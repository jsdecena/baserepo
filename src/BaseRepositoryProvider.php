<?php

namespace Jsdecena\Baserepo;

use Illuminate\Support\ServiceProvider;

class BaseRepositoryProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
    }
    
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(BaseRepository::class, function () {
            return new BaseRepository;
        });

        $this->app->alias(BaseRepository::class, 'baserepository');
    }
}