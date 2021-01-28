<?php
namespace Theanik\LaravelMoreCommand\Commands;

use Theanik\LaravelMoreCommand\Support\GenerateFile;
use Theanik\LaravelMoreCommand\Support\FileGenerator;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Support\Str;

class CreateTraitCommand extends CommandGenerator
{
    /**
     * argumentName
     *
     * @var string
     */
    public $argumentName = 'trait';

    /**
     * Name and signiture of Command.
     * name
     * @var string
     */
    protected $name = 'make:trait';

    /**
     * command description.
     * description
     * @var string
     */
    protected $description = 'Command description';
    
    /**
     * Get Command argumant EX : HasAuth
     * getArguments
     *
     * @return void
     */
    protected function getArguments()
    {
        return [
            ['trait', InputArgument::REQUIRED, 'The name of the trait'],
        ];
    }

        
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
       parent::__construct();
    }
    
    /**
     * getTraitName
     *
     * @return void
     */
    private function getTraitName()
    {
        $trait = Str::studly($this->argument('trait'));
        return $trait;
    }
    
    /**
     * getDestinationFilePath
     *
     * @return void
     */
    protected function getDestinationFilePath()
    {
        return app_path()."/Traits".'/'. $this->getTraitName() . '.php';
    }
    
    /**
     * getTraitNameWithoutNamespace
     *
     * @return void
     */
    private function getTraitNameWithoutNamespace()
    {
        return class_basename($this->getTraitName());
    }
    
    /**
     * getDefaultNamespace
     *
     * @return string
     */
    public function getDefaultNamespace() : string
    {
        return "App\\Traits";
    }

    /**
     * getStubFilePath
     *
     * @return void
     */
    protected function getStubFilePath()
    {
        $stub = '/stubs/traits.stub';
        return $stub;
    }
    
    /**
     * getTemplateContents
     *
     * @return void
     */
    protected function getTemplateContents()
    {
        return (new GenerateFile(__DIR__.$this->getStubFilePath(), [
            'CLASS_NAMESPACE'   => $this->getClassNamespace(),
            'CLASS'             => $this->getTraitNameWithoutNamespace()
        ]))->render();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $path = str_replace('\\', '/', $this->getDestinationFilePath());


        if (!$this->laravel['files']->isDirectory($dir = dirname($path))) {
            $this->laravel['files']->makeDirectory($dir, 0777, true);
        }

        $contents = $this->getTemplateContents();

        try {
            
            (new FileGenerator($path, $contents))->generate();

            $this->info("Created : {$path}");
        } catch (\Exception $e) {
            
            $this->error("File : {$e->getMessage()}");

            return E_ERROR;
        }

        return 0;

    }

}
