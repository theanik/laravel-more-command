<?php
namespace Theanik\LaravelMoreCommand\Commands;

use Theanik\LaravelMoreCommand\Support\GenerateFile;
use Theanik\LaravelMoreCommand\Support\FileGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Support\Str;

class CreateServiceCommand extends CommandGenerator
{    
    /**
     * argumentName
     *
     * @var string
     */
    public $argumentName = 'service';

        
    /**
     * Name and signiture of Command.
     * name
     * @var string
     */
    protected $name = 'make:service';


    /**
     * command description.
     * description
     * @var string
     */
    protected $description = 'Command description';


    
    /**
     * Get command agrumants - EX : UserService
     * getArguments
     *
     * @return void
     */
    protected function getArguments()
    {
        return [
            ['service', InputArgument::REQUIRED, 'The name of the service class.'],
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
     * Return Service name as convention
     * getServiceName
     *
     * @return void
     */
    private function getServiceName()
    {
        $service = Str::studly($this->argument('service'));

        if (Str::contains(strtolower($service), 'service') === false) {
            $service .= 'Service';
        }

        return $service;
    }
    
    /**
     * Return destination path for class file publish
     * getDestinationFilePath
     *
     * @return void
     */
    protected function getDestinationFilePath()
    {
        return app_path()."/Services".'/'. $this->getServiceName() . '.php';
    }
    
    
    /**
     * Return only service class name 
     * getServiceNameWithoutNamespace
     *
     * @return void
     */
    private function getServiceNameWithoutNamespace()
    {
        return class_basename($this->getServiceName());
    }
    
    /**
     * Set Default Namespace
     * Override CommandGenerator class method
     * getDefaultNamespace
     *
     * @return string
     */
    public function getDefaultNamespace() : string
    {
        return "App\\Services";
    }

    
    /**
     * Return stub file path
     * getStubFilePath
     *
     * @return void
     */
    protected function getStubFilePath()
    {
        $stub = '/stubs/service.stub';

        return $stub;
    }

    
    /**
     * Generate file content
     * getTemplateContents
     *
     * @return void
     */
    protected function getTemplateContents()
    {
        return (new GenerateFile(__DIR__.$this->getStubFilePath(), [
            'CLASS_NAMESPACE'   => $this->getClassNamespace(),
            'CLASS'             => $this->getServiceNameWithoutNamespace()
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
