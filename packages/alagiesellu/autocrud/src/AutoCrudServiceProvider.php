<?php

namespace Alagiesellu\Autocrud;

use Alagiesellu\Autocrud\Commands\GenerateAutoCRUDController;
use Alagiesellu\Autocrud\Commands\GenerateAutoCRUDFiles;
use Alagiesellu\Autocrud\Commands\GenerateAutoCRUDRepository;
use Alagiesellu\Autocrud\Commands\GenerateAutoCRUDResource;
use Alagiesellu\Autocrud\Commands\GenerateAutoCRUDService;
use Illuminate\Support\ServiceProvider;

class AutoCrudServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__.'/routes.php';

        $this->publishes([
            __DIR__ . '/config/autocrud.php' => config_path('autocrud.php'),
        ]);

        $this->commands([
            GenerateAutoCRUDFiles::class,
            GenerateAutoCRUDController::class,
            GenerateAutoCRUDService::class,
            GenerateAutoCRUDRepository::class,
            GenerateAutoCRUDResource::class,
        ]);
    }
}
