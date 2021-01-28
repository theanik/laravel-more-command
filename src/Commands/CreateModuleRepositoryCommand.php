<?php
namespace Theanik\LaravelMoreCommand\Commands;

use Theanik\LaravelMoreCommand\Support\GenerateFile;
use Theanik\LaravelMoreCommand\Support\FileGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Support\Str;

class CreateModuleRepositoryCommand extends CommandGenerator
{
    /**
     * argumentName
     *
     * @var string
     */
    public $argumentName = 'repository';

    /**
     * Name and signiture of Command.
     * name
     * @var string
     */
    protected $name = 'module:make-repository';

    /**
     * command description.
     * description
     * @var string
     */
    protected $description = 'Command description';


    /**
     * Get command agrumants - EX : UserRepository
     * getArguments
     *
     * @return void
     */
    protected function getArguments()
    {
        return [
            ['repository', InputArgument::REQUIRED, 'The name of the repository class.'],
            ['module', InputArgument::REQUIRED, 'The name of module will be used.'],
        ];
    }

    /**
     * Get the console command options or flag.
     * Ex -i : for creating interface
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['interface', 'i', InputOption::VALUE_NONE, 'Flag to create associated Interface', null],
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
     * Return Repository name as convention
     * getRepositoryName
     *
     * @return void
     */
    private function getRepositoryName()
    {
        $repository = Str::studly($this->argument('repository'));

        if (Str::contains(strtolower($repository), 'repository') === false) {
            $repository .= 'Repository';
        }

        return $repository;
    }

    /**
     * Return destination path for class file publish
     * getDestinationFilePath
     *
     * @return void
     */
    protected function getDestinationFilePath()
    {
        return base_path()."/Modules/{$this->argument('module')}"."/Repositories".'/'. $this->getRepositoryName() . '.php';
    }

    /**
     * Return Inferace name for this repository class
     * getInterfaceName
     *
     * @return void
     */
    protected function getInterfaceName()
    {
        return $this->getRepositoryName()."Interface";
    }

    /**
     * Return destination path for interface file publish
     * interfaceDestinationPath
     *
     * @return void
     */
    protected function interfaceDestinationPath()
    {
        return base_path()."/Modules/{$this->argument('module')}"."/Repositories/Interfaces".'/'. $this->getInterfaceName() . '.php';
    }

    /**
     * Return only repository class name 
     * getRepositoryNameWithoutNamespace
     *
     * @return void
     */
    private function getRepositoryNameWithoutNamespace()
    {
        return class_basename($this->getRepositoryName());
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
        return "Modules\\{$this->argument('module')}\\Repositorires";
    }

    /**
     * Return only repository interface name 
     * getInterfaceNameWithoutNamespace
     *
     * @return void
     */
    private function getInterfaceNameWithoutNamespace()
    {
        return class_basename($this->getInterfaceName());
    }

    /**
     * Set Default interface Namepsace
     * Override CommandGenerator class method
     * getDefaultInterfaceNamespace
     *
     * @return string
     */
    public function getDefaultInterfaceNamespace() : string
    {
        return "Modules\\{$this->argument('module')}\\Repositorires\\Interfaces";
    }


    /**
     * Return stub file path
     * getStubFilePath
     *
     * @return void
     */
    protected function getStubName()
    {
        if ($this->option('interface') === true) {
            $stub = '/stubs/repository-interface.stub';
        } else {
            $stub = '/stubs/repository.stub';
        }

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
        return (new GenerateFile(__DIR__.$this->getStubName(), [
            'CLASS_NAMESPACE'   => $this->getClassNamespace(),
            'INTERFACE_NAMESPACE'   => $this->getInterfaceNamespace().'\\'.$this->getInterfaceNameWithoutNamespace(),
            'CLASS'             => $this->getRepositoryNameWithoutNamespace(),
            'INTERFACE'         => $this->getInterfaceNameWithoutNamespace()
        ]))->render();
    }


    /**
     * Generate inteface file content
     * getInterfaceTemplateContents
     *
     * @return void
     */
    protected function getInterfaceTemplateContents()
    {
        return (new GenerateFile(__DIR__."/stubs/interface.stub", [
            'CLASS_NAMESPACE'   => $this->getInterfaceNamespace(),
            'INTERFACE'         => $this->getInterfaceNameWithoutNamespace()
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

        // For Interface
        if($this->option('interface') === true){
            $interfacePath = str_replace('\\', '/', $this->interfaceDestinationPath());

            if (!$this->laravel['files']->isDirectory($dir = dirname($interfacePath))) {
                $this->laravel['files']->makeDirectory($dir, 0777, true);
            }

            $interfaceContents = $this->getInterfaceTemplateContents();
        }

        try {
            
            (new FileGenerator($path, $contents))->generate();

            $this->info("Created : {$path}");

            // For Interface
            if($this->option('interface') === true){

                (new FileGenerator($interfacePath, $interfaceContents))->generate();

                $this->info("Created : {$interfacePath}");
            }
        } catch (\Exception $e) {

            $this->error("File : {$e->getMessage()}");

            return E_ERROR;
        }

        return 0;
    }

}
