<?php

namespace Iggy\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

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
     * @var bool
     */
    private $allowPathArgument;

    /**
     * InitCommand constructor.
     *
     * @param string $skelDirectory
     * @param bool $allowPathArgument
     */
    public function __construct(string $skelDirectory = null, bool $allowPathArgument = true)
    {
        $this->skelDirectory     = $skelDirectory ?: __DIR__ . '/../Resource/skel/';
        $this->allowPathArgument = $allowPathArgument;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('init');
        $this->setDescription('Initialize a basic Iggy template site in this (or the specified) directory');
        $this->addOption('force',  'f', InputOption::VALUE_REQUIRED, 'Deploy even if directory is not empty');

        if ($this->allowPathArgument) {
            $this->addArgument('path', InputArgument::OPTIONAL, 'The path to deploy to', getcwd());
        }
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

        $outPath = $input->getArgument('path');

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
        (new Filesystem())->mirror($this->skelDirectory, $outPath);
        $io->success('Deployed to: ' . $outPath);

        return 0;
    }
}