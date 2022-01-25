<?php

namespace Alagiesellu\Autocrud\Commands;

use Illuminate\Console\GeneratorCommand;

class GenerateAutoCRUDResource extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autocrud:make-resource {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create auto CRUD resource.';


    protected function getStub(): string
    {
        return  base_path('stubs/Resource.stub');
    }

    /**
     * The root location where your new file should
     * be written to.
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . 'app\Autocrud\Resources';
    }
}
