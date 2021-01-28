<?php

namespace Theanik\LaravelMoreCommand\Support;

class GenerateFile{

    protected $path;

    protected static $basePath = null;

    protected $replaces = [];


    public function __construct($path, array $replaces = [])
    {
        $this->path = $path;
        $this->replaces = $replaces;
    }

    public function getPath()
    {
        return $this->path;
        // $path = static::getBasePath() . $this->path;

        // return file_exists($path) ? $path : __DIR__ . '/../Commands/stubs' . $this->path;
    }


    public function getContents()
    {
        $contents = file_get_contents($this->getPath());

        foreach ($this->replaces as $search => $replace) {
            $contents = str_replace('$' . strtoupper($search) . '$', $replace, $contents);
        }

        return $contents;
    }


    public function render()
    {
        return $this->getContents();
    }

}