<?php

namespace Iggy\Console;

use Symfony\Component\Console\Command\Command;

/**
 * Class InitCommand
 * @package Iggy\Console
 */
class InitCommand extends Command
{
    /**
     * @var string
     */
    private $defaultOutputDir;
    /**
     * @var string
     */
    private $skelDirectory;

    /**
     * InitCommand constructor.
     *
     * @param string $defaultOutputDir
     * @param string $skelDirectory
     */
    public function __construct(string $defaultOutputDir, string $skelDirectory)
    {
        $this->defaultOutputDir = $defaultOutputDir;
        $this->skelDirectory = $skelDirectory;
        parent::__construct();
    }

    // TODO: Copy from InstalIggy into here..
    // options: --path
    // will recursively copy the skel directory to cwd (or whatever path is specified) // TODO: fix skel contents
    // will prompt if no --force and there are any files in the desired path (if -n, then will fail without --force)
}