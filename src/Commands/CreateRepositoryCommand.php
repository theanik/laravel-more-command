<?php

namespace Theanik\LaravelMoreCommand\Commands;

use Theanik\LaravelMoreCommand\Support\GenerateFile;
use Theanik\LaravelMoreCommand\Support\FileGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Support\Str;

class CreateRepositoryCommand extends CommandGenerator
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
    protected $name = 'make:repository';


    /**
     * command description.
     * description
     * @var string
     */
    protected $description = 'Command description';


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
     * Get command agrumants - EX : UserRepository
     * getArguments
     *
     * @return array[]
     */
    protected function getArguments(): array
    {
        return [
            ['repository', InputArgument::REQUIRED, 'The name of the repository class.'],
        ];
    }

    /**
     * Get command options - EX : -i
     * getOptions
     *
     * @return array[]
     */
    protected function getOptions(): array
    {
        return [
            ['interface', 'i', InputOption::VALUE_NONE, 'Flag to create associated Interface', null]
        ];
    }

    /**
     * Return Repository name as convention
     * getRepositoryName
     *
     * @return string
     */
    private function getRepositoryName(): string
    {
        $repository = Str::studly($this->argument('repository'));

        if (Str::contains(strtolower($repository), 'repository') === false) {
            $repository .= 'Repository';
        }

        return $repository;
    }

    /**
     * Replace App with empty string for resolve namespace
     *
     * @return string
     */
    private function resolveNamespace(): string
    {
        if (strpos($this->getServiceNamespaceFromConfig(), self::APP_PATH) === 0) {
            return str_replace(self::APP_PATH, '', $this->getServiceNamespaceFromConfig());
        }
        return '/' . $this->getServiceNamespaceFromConfig();
    }

    /**
     * Return destination path for class file publish
     * getDestinationFilePath
     *
     * @return string
     */
    protected function getDestinationFilePath(): string
    {
        return app_path() . $this->resolveNamespace() . '/Repositories' . '/' . $this->getRepositoryName() . '.php';
    }

    /**
     * Return Inference name for this repository class
     * getInterfaceName
     *
     * @return string
     */
    protected function getInterfaceName(): string
    {
        return $this->getRepositoryName() . "Interface";
    }


    /**
     * Return destination path for interface file publish
     * interfaceDestinationPath
     *
     * @return string
     */
    protected function interfaceDestinationPath(): string
    {
        return app_path() . $this->resolveNamespace() . "/Repositories/Interfaces" . '/' . $this->getInterfaceName() . '.php';
    }


    /**
     * Return only repository class name
     * getRepositoryNameWithoutNamespace
     *
     * @return string
     */
    private function getRepositoryNameWithoutNamespace(): string
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
    public function getDefaultNamespace(): string
    {
        $configNamespace = $this->getRepositoryNamespaceFromConfig();
        return "$configNamespace\\Repositories";
    }


    /**
     * Return only repository interface name
     * getInterfaceNameWithoutNamespace
     *
     * @return string
     */
    private function getInterfaceNameWithoutNamespace(): string
    {
        return class_basename($this->getInterfaceName());
    }

    /**
     * Set Default interface Namespace
     * Override CommandGenerator class method
     * getDefaultInterfaceNamespace
     *
     * @return string
     */
    public function getDefaultInterfaceNamespace(): string
    {
        $configNamespace = $this->getRepositoryNamespaceFromConfig();
        return "$configNamespace\\Repositories\\Interfaces";
    }


    /**
     * Return stub file path
     * getStubFilePath
     *
     * @return string
     */
    protected function getStubFilePath(): string
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
     * @return string
     */
    protected function getTemplateContents(): string
    {
        return (new GenerateFile(__DIR__ . $this->getStubFilePath(), [
            'CLASS_NAMESPACE' => $this->getClassNamespace(),
            'INTERFACE_NAMESPACE' => $this->getInterfaceNamespace() . '\\' . $this->getInterfaceNameWithoutNamespace(),
            'CLASS' => $this->getRepositoryNameWithoutNamespace(),
            'INTERFACE' => $this->getInterfaceNameWithoutNamespace()
        ]))->render();
    }


    /**
     * Generate interface file content
     * getInterfaceTemplateContents
     *
     * @return string
     */
    protected function getInterfaceTemplateContents(): string
    {
        return (new GenerateFile(__DIR__ . "/stubs/interface.stub", [
            'CLASS_NAMESPACE' => $this->getInterfaceNamespace(),
            'INTERFACE' => $this->getInterfaceNameWithoutNamespace()
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
        if ($this->option('interface') == true) {
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
            if ($this->option('interface') === true) {

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
