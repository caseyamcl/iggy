<?php

namespace Iggy\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class InitCommand
 * @package Iggy\Console
 */
class InitCommand extends Command
{
    /**
     * @var string
     */
    private $skelDirectory;

    /**
     * InitCommand constructor.
     *
     * @param string $skelDirectory
     */
    public function __construct(string $skelDirectory)
    {
        $this->skelDirectory = $skelDirectory;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('init');
        $this->setDescription('Initialize a basic Iggy template site in this (or the specified) directory');
        $this->addOption('path', 'p', InputOption::VALUE_REQUIRED, 'The path to deploy to', getcwd());
        $this->addOption('force',  'f', InputOption::VALUE_REQUIRED, 'Deploy even if directory is not empty');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $outPath = $input->getOption('path');

        if (! is_writable($outPath)) {
            throw new \RuntimeException('Output path is not writable: ' . $outPath);
        }

        if (! is_dir($outPath)) {
            throw new \RuntimeException('Output path must be a directory (not a file): ' . $outPath);
        }

        $directoryIsEmpty = (count(scandir($outPath)) == 2);
        if (! $directoryIsEmpty) {
            $io->warning('Directory is not empty: ' . $outPath);

            if (! $input->getOption('force')) {
                $continue = $input->isInteractive()
                ? $io->confirm('Initialize anyway?')
                : false;

                if (! $continue) {
                    $io->warning('Aborting.');
                    return 1;
                }
            }
        }

        // Get the current working directory
        $this->recursiveCopy($this-$this->skelDirectory, $outPath);
        $io->success('Deployed to: ' . $outPath);

        return 0;
    }

    /**
     * Recursive copy function
     *
     * Credit: http://stackoverflow.com/a/7775949/143201
     *
     * @param string $source
     * @param string $destination
     */
    protected function recursiveCopy($source, $destination)
    {
        /** @var \RecursiveIteratorIterator|\RecursiveDirectoryIterator $iterator */
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $item) {
            if ($item->isDir()) {
                mkdir($destination . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
            } else {
                copy($item, $destination . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
            }
        }
    }
}