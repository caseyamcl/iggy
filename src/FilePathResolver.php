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

    private static $defaultSubSearchFiles = ['index.twig', 'index.html', 'index.htm'];

    /**
     * @var string
     */
    private $basePath;

    /**
     * @var array|string[]
     */
    private $subSearchFiles;

    /**
     * RequestFileResolver constructor.
     *
     * @param string $basePath
     * @param array $subSearchFiles
     */
    public function __construct($basePath, array $subSearchFiles = self::DEFAULT)
    {
        $this->basePath = $basePath;
        $this->subSearchFiles = $subSearchFiles ?: self::$defaultSubSearchFiles;
    }

    /**
     * @param string $path  The path section of the URI
     * @return \SplFileInfo|null
     */
    public function resolvePath($path)
    {
        // The specific path that the user asked for (could map to a directory)
        $fullPath = Path::join($this->basePath, $path);

        // Additional paths to search for if the exact path was not matched
        $searchPaths = array_merge([$fullPath], array_map(function ($pattern) use ($fullPath) {
            return Path::join($fullPath . $pattern);
        }, $this->subSearchFiles));

        // Do the search
        foreach ($searchPaths as $path) {
            if (is_readable($path)) {
                return new \SplFileInfo($path);
            }
        }

        // If made it here.
        return null;
    }
}