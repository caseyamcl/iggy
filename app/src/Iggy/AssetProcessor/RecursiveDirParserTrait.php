<?php
/**
 * Created by PhpStorm.
 * User: casey
 * Date: 11/5/14
 * Time: 4:06 PM
 */

namespace Iggy\AssetProcessor;

/**
 * Adds recursive directory parsing to asset processors that support it
 *
 * @package Iggy\AssetProcessor
 */
trait RecursiveDirParserTrait
{
    /**
     * Get file iterator for path (file or directory)
     *
     * @param $fileOrDirPath
     * @return \SplFileInfo[]
     */
    protected function getFileIterator($fileOrDirPath)
    {
        //If is a file, just return an iterator with a single file
        if (is_file($fileOrDirPath)) {
           $arr = [$fileOrDirPath];
        }
        else {
            // Else if directory, get all files as basic iterator..
            $allFiles = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($fileOrDirPath));

            // Convert it to an array..
            $arr = iterator_to_array($allFiles);
            usort($arr, function(\SplFileInfo $a, \SplFileInfo $b) {
                return strcmp($a->getPathname(), $b->getPathname());
            });
        }

        return new \ArrayIterator($arr);
    }
}

/* EOF: RecursiveDirParserTrait.php */ 