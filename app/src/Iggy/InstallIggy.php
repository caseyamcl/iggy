<?php

/**
 * Iggy Rapid Prototyping App
 *
 * @license http://opensource.org/licenses/MIT
 * @link https://github.com/caseyamcl/iggy
 * @version 1.0
 * @package caseyamcl/iggy
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * ------------------------------------------------------------------
 */

namespace Iggy;

use Composer\Script\Event;

/**
 * InstallIggy Iggy Composer Command
 *
 * Runs as part of `composer create-project` command
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 */
class InstallIggy
{
    /**
     * Event listener for PostCreateProject
     *
     * @param Event $event
     */
    public static function postCreateProject(Event $event)
    {
        $cwd = getcwd();

        if (file_exists($cwd . '/assets') OR file_exists($cwd . '/content')) {
            $event->getIO()->write('Skipping creating skeleton project; existing content or assets detected');
        }

        // Get the current working directory
        self::recursiveCopy($cwd . '/app/skel', $cwd);

        $event->getIO()->write('Creating skeleton project');
    }

    // ---------------------------------------------------------------

    /**
     * Recursive copy function
     *
     * Lifted from http://stackoverflow.com/a/7775949/143201
     *
     * @param string $source
     * @param string $dest
     */
    protected static function recursiveCopy($source, $dest)
    {
        // Create iterator
        $iterator = new \RecursiveIteratorIterator(
          new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS)
        );

        // Do the copy
        foreach ($iterator as $item) {
            if ($item->isDir()) {
                mkdir($dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
            } else {
                copy($item, $dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
            }
        }
    }
}
