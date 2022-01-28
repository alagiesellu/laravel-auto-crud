<?php

namespace Alagiesellu\Autocrud\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;

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
    protected $type = 'Resource';


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

    protected function getArguments(): array
    {
        return [
//            ['name', InputArgument::REQUIRED, 'The name and root of the file.'],
            ['model', InputArgument::REQUIRED, 'The name and root of the model class targeted.'],
        ];
    }
}
