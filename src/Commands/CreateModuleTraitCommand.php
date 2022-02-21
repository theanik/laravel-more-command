<?php
namespace Theanik\LaravelMoreCommand\Commands;

use Theanik\LaravelMoreCommand\Support\GenerateFile;
use Theanik\LaravelMoreCommand\Support\FileGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Str;
class CreateModuleTraitCommand extends CommandGenerator
{
    /**
     * argumentName
     *
     * @var string
     */
    public $argumentName = 'trait';

    /**
     * Name and signature of Command.
     * name
     * @var string
     */
    protected $name = 'module:make-trait';

     /**
     * command description.
     * description
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Get command arguments - EX : HasAuth
     * getArguments
     *
     * @return array
     */
    protected function getArguments(): array
    {
        return [
            ['trait', InputArgument::REQUIRED, 'The name of the trait'],
            ['module', InputArgument::REQUIRED, 'The name of module will be used.']
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
     * @return string
     */
    private function getTraitName(): string
    {
        return Str::studly($this->argument('trait'));
    }

    /**
     * getDestinationFilePath
     *
     * @return string
     */
    protected function getDestinationFilePath(): string
    {
        return base_path()."/Modules/{$this->argument('module')}"."/Traits".'/'. $this->getTraitName() . '.php';
    }


    /**
     * getTraitNameWithoutNamespace
     *
     * @return string
     */
    private function getTraitNameWithoutNamespace(): string
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
        return "Modules\\{$this->argument('module')}\\Traits";
    }


    /**
     * getStubFilePath
     *
     * @return string
     */
    protected function getStubFilePath(): string
    {
        return '/stubs/traits.stub';
    }

    /**
     * getTemplateContents
     *
     * @return string
     */
    protected function getTemplateContents(): string
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
