<?php

namespace Iggy;
use Webmozart\PathUtil\Path;

/**
 * Class RequestFileResolver
 * @package Iggy
 */
class FilePathResolver
{
    const DEFAULT = null;

    /**
     * @var string
     */
    private $basePath;

    /**
     * @var array|string[]
     */
    private $indexBaseName;

    /**
     * @var array|string[]
     */
    private $defaultExtensions;

    /**
     * RequestFileResolver constructor.
     *
     * @param string $basePath
     * @param string $indexBasename
     * @param array $defaultExtensions
     */
    public function __construct(string $basePath, string $indexBasename = 'index', array $defaultExtensions = [])
    {
        $this->basePath = $basePath;
        $this->indexBaseName = $indexBasename;
        $this->defaultExtensions = $defaultExtensions;
    }

    /**
     * @param string $path  The path section of the URI
     * @return \SplFileInfo|null
     */
    public function resolvePath($path): ?\SplFileInfo
    {
        if (is_readable(Path::join($this->basePath, $path))) {
            return new \SplFileInfo(Path::join($this->basePath, $path));
        }

        foreach ($this->defaultExtensions as $extension) {
            $fn = Path::join($this->basePath, $path . '.' . ltrim($extension, '.'));
            if (is_readable($fn)) {
                return new \SplFileInfo($fn);
            }
        }

        // If made it here, return NULL (will be interpreted as 404)
        return null;
    }

}