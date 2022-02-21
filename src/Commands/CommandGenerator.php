<?php

namespace Theanik\LaravelMoreCommand\Commands;

use Illuminate\Console\Command;

abstract class CommandGenerator extends Command
{
    public const APP_PATH = 'App';

    /**
     * argumentName
     *
     * @var mixed
     */
    public $argumentName;

    /**
     * Return the rendered File Content
     * getTemplateContents
     *
     * @return string
     */
    abstract protected function getTemplateContents(): string;


    /**
     * Return the destination path for publish created class file.
     * getDestinationFilePath
     *
     * @return string
     */
    abstract protected function getDestinationFilePath(): string;


    /**
     * Get Repository Namespace From Config
     * @return string
     */
    public function getRepositoryNamespaceFromConfig(): string
    {
        return config('laravel-more-command.repository-namespace') ?? 'App';
    }


    /**
     * Get Service Namespace From Config
     * @return string
     */
    public function getServiceNamespaceFromConfig(): string
    {
        return config('laravel-more-command.service-namespace') ?? 'App';
    }

    /**
     * Return the default namespace for class
     * getDefaultNamespace
     *
     * @return string
     */
    public function getDefaultNamespace(): string
    {
        return '';
    }


    /**
     * Return the default namespace type for interface
     * getDefaultInterfaceNamespace
     *
     * @return string
     */
    public function getDefaultInterfaceNamespace(): string
    {
        return '';
    }


    /**
     * Return a class name
     * getClass
     *
     * @return string
     */
    public function getClass(): string
    {
        return class_basename($this->argument($this->argumentName));
    }


    /**
     * Generate class namespace dynamically
     * getClassNamespace
     *
     * @return string
     */
    public function getClassNamespace(): string
    {
        $extra = str_replace(array($this->getClass(), '/'), array('', '\\'), $this->argument($this->argumentName));

        $namespace = $this->getDefaultNamespace();

        $namespace .= '\\' . $extra;

        $namespace = str_replace('/', '\\', $namespace);

        return trim($namespace, '\\');
    }


    /**
     * Generate interface namespace dynamically
     * getInterfaceNamespace
     *
     * @return string
     */
    public function getInterfaceNamespace(): string
    {
        $extra = str_replace(array($this->getClass() . 'Interface', '/'), array('', '\\'), $this->argument($this->argumentName) . 'Interface');

        $namespace = $this->getDefaultInterfaceNamespace();

        $namespace .= '\\' . $extra;

        $namespace = str_replace('/', '\\', $namespace);

        return trim($namespace, '\\');
    }


    /**
     * checkModuleExists
     *
     * @param mixed $moduleName
     * @return bool
     */
    public function checkModuleExists(string $moduleName): bool
    {
        if (!in_array($moduleName, scandir(base_path() . "/Modules"))) {
            return false;
        }
        return true;
    }

}
