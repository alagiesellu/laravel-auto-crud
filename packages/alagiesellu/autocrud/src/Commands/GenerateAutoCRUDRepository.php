<?php

namespace Alagiesellu\Autocrud\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;

class GenerateAutoCRUDRepository extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autocrud:make-repository {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create auto CRUD repository.';


    protected function getStub(): string
    {
        return  base_path('stubs/Repository.stub');
    }

    /**
     * The root location where your new file should
     * be written to.
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . 'app\Autocrud\Repositories';
    }

    protected function getArguments(): array
    {
        return [
//            ['name', InputArgument::REQUIRED, 'The name and root of the file.'],
            ['model', InputArgument::REQUIRED, 'The name and root of the model class targeted.'],
        ];
    }
}
