<?php

namespace Alagiesellu\Autocrud\Commands;

use Illuminate\Console\Command;

class GenerateAutoCRUDFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autocrud:make {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command controller, service, repository and resource.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $model = $this->argument('model');

        $this->call(GenerateAutoCRUDController::class, [
            'name' => "{$model}Controller",
            'model' => $model,
        ]);

        $this->call(GenerateAutoCRUDService::class, [
            'name' => "{$model}Service",
            'model' => $model,
        ]);

        $this->call(GenerateAutoCRUDRepository::class, [
            'name' => "{$model}Repository",
            'model' => $model,
        ]);

        $this->call(GenerateAutoCRUDResource::class, [
            'name' => "{$model}Resource",
            'model' => $model,
        ]);

        $this->info("Autocrud make successfully for {$model}.");
    }
}
