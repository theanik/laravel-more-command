<?php
namespace Theanik\LaravelMoreCommand\Support;

use Illuminate\Filesystem\Filesystem;

class FileGenerator{
        
    /**
     * path
     *
     * @var mixed
     */
    protected $path;
    
    /**
     * contents
     *
     * @var mixed
     */
    protected $contents;
    
    /**
     * filesystem
     *
     * @var mixed
     */
    protected $filesystem;
    
    /**
     * __construct
     *
     * @param  mixed $path
     * @param  mixed $contents
     * @param  mixed $filesystem
     * @return void
     */
    public function __construct($path, $contents, $filesystem = null)
    {
        $this->path = $path;
        $this->contents = $contents;
        $this->filesystem = $filesystem ?: new Filesystem();
    }
    
    /**
     * getContents
     *
     * @return void
     */
    public function getContents()
    {
        return $this->contents;
    }
    
    /**
     * setContents
     *
     * @param  mixed $contents
     * @return void
     */
    public function setContents($contents)
    {
        $this->contents = $contents;

        return $this;
    }
    
    /**
     * getFilesystem
     *
     * @return void
     */
    public function getFilesystem()
    {
        return $this->filesystem;
    }
    
    /**
     * setFilesystem
     *
     * @param  mixed $filesystem
     * @return void
     */
    public function setFilesystem(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;

        return $this;
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
     * setPath
     *
     * @param  mixed $path
     * @return void
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }
    
    /**
     * withFileOverwrite
     *
     * @param  mixed $overwrite
     * @return FileGenerator
     */
    public function withFileOverwrite(bool $overwrite): FileGenerator
    {
        $this->overwriteFile = $overwrite;

        return $this;
    }

        
    /**
     * generate the file
     * generate
     *
     * @return void
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
