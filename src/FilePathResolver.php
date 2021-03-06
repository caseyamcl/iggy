<?php

namespace Iggy;

use Webmozart\PathUtil\Path;

/**
 * Class RequestFileResolver
 *
 * @package Iggy
 */
class FilePathResolver
{
    const DEFAULT_FILES    = ['index.twig', 'index.html', 'index.htm', 'index.html.twig'];
    const DEFAULT_SUFFIXES = ['twig', 'html', 'html.twig'];

    /**
     * @var array|string[]
     */
    private $basePaths;

    /**
     * @var array|string[]
     */
    private $subSearchFiles;

    /**
     * @var array
     */
    private $searchSuffixes;

    /**
     * RequestFileResolver constructor.
     *
     * @param string|array|string[] $basePaths
     * @param array $searchFiles
     * @param array $searchSuffixes
     */
    public function __construct(
        $basePaths,
        array $searchFiles = self::DEFAULT_FILES,
        array $searchSuffixes = self::DEFAULT_SUFFIXES
    ) {
        $this->basePaths      = (array) $basePaths;
        $this->subSearchFiles = $searchFiles;
        $this->searchSuffixes = $searchSuffixes;
    }

    /**
     * @param string $path
     * @return null|\SplFileInfo
     */
    public function resolvePath(string $path): ?\SplFileInfo
    {
        foreach ($this->basePaths as $basePath) {
            if ($fileInfo = $this->doResolvePath($basePath, $path)) {
                return $fileInfo;
            }
        }

        return null;
    }

    /**
     * @param string $basePath
     * @param string $path The path section of the URI
     * @return \SplFileInfo|null
     */
    protected function doResolvePath(string $basePath, string $path): ?\SplFileInfo
    {
        // The specific path that the user asked for (could map to a directory)
        $fullPath = Path::join($basePath, $path);

        // If is directory, then search sub-files
        if (is_dir($fullPath)) {
            foreach ($this->subSearchFiles as $subSearchFile) {
                $subFullPath = Path::join($fullPath, $subSearchFile);
                if (is_readable($subFullPath)) {
                    return new \SplFileInfo($subFullPath);
                }
            }
        }
        // If is a file and readable, great.
        elseif (is_readable($fullPath)) {
            return new \SplFileInfo($fullPath);
        }
        // If not found, attempt to add auto-suffixes before giving up
        elseif (Path::getExtension($fullPath) == '') {
            foreach ($this->searchSuffixes as $searchSuffix) {
                $sufFullPath = Path::changeExtension($fullPath, $searchSuffix);
                if (is_readable($sufFullPath)) {
                    return new \SplFileInfo($sufFullPath);
                }
            }
        }

        // If made it here.
        return null;
    }
}