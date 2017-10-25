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
    public function __construct(string $basePath, array $subSearchFiles = self::DEFAULT)
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

        if (is_dir($fullPath)) {
            $searchPaths = array_map(function ($fileName) use ($fullPath) {
                return Path::join($fullPath, $fileName);
            }, $this->subSearchFiles);
        }
        else {
            $searchPaths = [$fullPath];
        }

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