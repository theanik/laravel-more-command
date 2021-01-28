<?php

namespace Theanik\LaravelMoreCommand\Commands;

use Illuminate\Console\Command;

abstract class CommandGenerator extends Command{
    
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
    abstract protected function getTemplateContents();
    

    /**
     * Return the destination path for publishe created class file.
     * getDestinationFilePath
     *
     * @return string
     */
    abstract protected function getDestinationFilePath();
    

    /**
     * Return the default namesapce for class
     * getDefaultNamespace
     *
     * @return string
     */
    public function getDefaultNamespace() : string
    {
        return '';
    }

    
    /**
     * Return the default namesapce type for interface
     * getDefaultInterfaceNamespace
     *
     * @return string
     */
    public function getDefaultInterfaceNamespace() : string
    {
        return '';
    }

        
    /**
     * Return a vaid class name
     * getClass
     *
     * @return void
     */
    public function getClass()
    {
        return class_basename($this->argument($this->argumentName));
    }

    
    /**
     * Generate class namespace dinamacally
     * getClassNamespace
     *
     * @return void
     */
    public function getClassNamespace()
    {
        $extra = str_replace($this->getClass(), '', $this->argument($this->argumentName));

        $extra = str_replace('/', '\\', $extra);

        $namespace =  $this->getDefaultNamespace();

        $namespace .= '\\' . $extra;

        $namespace = str_replace('/', '\\', $namespace);

        return trim($namespace, '\\');
    }


    
    /**
     * Generate interface namespace dinamacally
     * getInterfaceNamespace
     *
     * @return void
     */
    public function getInterfaceNamespace()
    {
        $extra = str_replace($this->getClass().'Interface', '', $this->argument($this->argumentName).'Interface');

        $extra = str_replace('/', '\\', $extra);

        $namespace =  $this->getDefaultInterfaceNamespace();

        $namespace .= '\\' . $extra;

        $namespace = str_replace('/', '\\', $namespace);

        return trim($namespace, '\\');
    }




}