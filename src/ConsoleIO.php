<?php

namespace Iggy;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class ConsoleIO
 * @package Iggy
 */
class ConsoleIO extends SymfonyStyle
{
    /**
     * Write a line to output with logging prefixes
     *
     * @param string $message
     */
    public function log(string $message)
    {
        if ($this->getVerbosity() >= OutputInterface::VERBOSITY_VERY_VERBOSE) {
            $date = date('Y-m-d H:i:s');
        }
        elseif ($this->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
            $date = date('H:i:s');
        }
        else {
            $date = '';
        }

        if ($this->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
            $memReport = sprintf(
                '[%s:%s]',
                \ByteUnits\bytes(memory_get_usage())->format(),
                \ByteUnits\bytes(memory_get_peak_usage())->format()
            );
        }
        else {
            $memReport = '';
        }

        $this->writeln(sprintf('<fg=white>%s %s</fg=white> %s', $date, $memReport, $message));
    }

}