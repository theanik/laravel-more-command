<?php

namespace Theanik\LaravelMoreCommand\Support;

class GenerateFile{

    /**
     * path
     *
     * @var string
     */
    protected $path;

    /**
     * basePath
     *
     * @var string
     */
    protected static $basePath = '';

    /**
     * replaces
     *
     * @var array
     */
    protected $replaces = [];


    /**
     * __construct
     *
     * @param  string $path
     * @param  array $replaces
     * @return void
     */
    public function __construct(string $path, array $replaces = [])
    {
        $this->path = $path;
        $this->replaces = $replaces;
    }

    /**
     * getPath
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }


    /**
     * Get replaced file content
     * getContents
     *
     * @return string
     */
    public function getContents(): string
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
     * @return string
     */
    public function render(): string
    {
        return $this->getContents();
    }

}
