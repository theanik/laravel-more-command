<?php
namespace Theanik\LaravelMoreCommand\Commands;

use Theanik\LaravelMoreCommand\Support\GenerateFile;
use Theanik\LaravelMoreCommand\Support\FileGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Str;

class CreateModuleBladeCommand extends CommandGenerator
{
    /**
     * argumentName
     *
     * @var string
     */
    public $argumentName = 'view';

    /**
     * Name and signiture of Command.
     * name
     * @var string
     */
    protected $name = 'module:make-view';

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
            ['view', InputArgument::REQUIRED, 'The name of the view'],
            ['module', InputArgument::REQUIRED, 'The name of module will be used.'],
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
     * getViewName
     *
     * @return void
     */
    private function getViewName()
    {
        $view = Str::camel($this->argument('view'));
        if (Str::contains(strtolower($view), '.blade.php') === false) {
            $view .= '.blade.php';
        }
        return $view;
    }
    
    /**
     * getDestinationFilePath
     *
     * @return void
     */
    protected function getDestinationFilePath()
    {
        return base_path()."/Modules/{$this->argument('module')}"."/Resources/views".'/'. $this->getViewName();
    }
    

    /**
     * getStubFilePath
     *
     * @return void
     */
    protected function getStubFilePath()
    {
        $stub = '/stubs/blade.stub';
        return $stub;
    }
    
    /**
     * getTemplateContents
     *
     * @return void
     */
    protected function getTemplateContents()
    {
        return (new GenerateFile(__DIR__.$this->getStubFilePath()))->render();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Check this module exists or not.
        if ($this->checkModuleExists($this->argument('module')) === false) {
            $this->error(" Module [{$this->argument('module')}] does not exist!");  
            return E_ERROR;
            exit;
         }

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
