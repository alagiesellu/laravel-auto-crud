<?php

namespace App\Console;

use Alagiesellu\Autocrud\Commands\GenerateAutoCRUDController;
use Alagiesellu\Autocrud\Commands\GenerateAutoCRUDFiles;
use Alagiesellu\Autocrud\Commands\GenerateAutoCRUDRepository;
use Alagiesellu\Autocrud\Commands\GenerateAutoCRUDResource;
use Alagiesellu\Autocrud\Commands\GenerateAutoCRUDService;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        GenerateAutoCRUDFiles::class,
        GenerateAutoCRUDController::class,
        GenerateAutoCRUDRepository::class,
        GenerateAutoCRUDResource::class,
        GenerateAutoCRUDService::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
