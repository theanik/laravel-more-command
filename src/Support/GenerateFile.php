<?php

namespace Theanik\LaravelMoreCommand\Support;

class GenerateFile{
    
    /**
     * path
     *
     * @var mixed
     */
    protected $path;

    /**
     * basePath
     *
     * @var mixed
     */
    protected static $basePath = null;
    
    /**
     * replaces
     *
     * @var array
     */
    protected $replaces = [];

    
    /**
     * __construct
     *
     * @param  mixed $path
     * @param  mixed $replaces
     * @return void
     */
    public function __construct($path, array $replaces = [])
    {
        $this->path = $path;
        $this->replaces = $replaces;
    }
    
    /**
     * getPath
     *
     * @return void
     */
    public function getPath()
    {
        return $this->path;
    }

    
    /**
     * Get replaced file content
     * getContents
     *
     * @return void
     */
    public function getContents()
    {
        $contents = file_get_contents($this->getPath());

        foreach ($this->replaces as $search => $replace) {
            $contents = str_replace('$' . strtoupper($search) . '$', $replace, $contents);
        }

        return $contents;
    }

    
    /**
     * return the replaced file content
     * render
     *
     * @return void
     */
    public function render()
    {
        return $this->getContents();
    }

}
