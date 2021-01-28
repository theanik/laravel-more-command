<?php
namespace Theanik\LaravelMoreCommand\Support;

use Illuminate\Filesystem\Filesystem;

class FileGenerator{
    
    protected $path;

   
    protected $contents;

    protected $filesystem;


    public function __construct($path, $contents, $filesystem = null)
    {
        $this->path = $path;
        $this->contents = $contents;
        $this->filesystem = $filesystem ?: new Filesystem();
    }

    public function getContents()
    {
        return $this->contents;
    }

    public function setContents($contents)
    {
        $this->contents = $contents;

        return $this;
    }

    public function getFilesystem()
    {
        return $this->filesystem;
    }

    public function setFilesystem(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;

        return $this;
    }


    public function getPath()
    {
        return $this->path;
    }

   
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    public function withFileOverwrite(bool $overwrite): FileGenerator
    {
        $this->overwriteFile = $overwrite;

        return $this;
    }

    /**
     * Generate the file.
     */
    public function generate()
    {
        $path = $this->getPath();
        if (!$this->filesystem->exists($path)) {
            return $this->filesystem->put($path, $this->getContents());
        }

        throw new \Exception('File already exists!');
    }







}