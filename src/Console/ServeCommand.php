<?php

namespace Iggy\Console;

use Iggy\ConsoleIO;
use Iggy\RequestHandlerFactory;
use Psr\Http\Message\ServerRequestInterface;
use React\EventLoop\Factory;
use React\Http\Server;
use React\Socket\Server as SocketServer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ServeCommand
 * @package Iggy\Console
 */
class ServeCommand extends Command
{
    /**
     * @var RequestHandlerFactory
     */
    private $requestHandlerFactory;

    /**
     * @var bool
     */
    private $allowOptions;

    /**
     * ServeCommand constructor.
     * @param RequestHandlerFactory $requestHandlerFactory
     * @param bool $allowOptions
     */
    public function __construct(RequestHandlerFactory $requestHandlerFactory, bool $allowOptions = true)
    {
        $this->requestHandlerFactory = $requestHandlerFactory;
        $this->allowOptions = $allowOptions;

        parent::__construct();

    }

    protected function configure()
    {
        $this->setName('serve');
        $this->setDescription('Run Iggy development server');

        if ($this->allowOptions) {
            $this->addOption('listen', 'l', InputOption::VALUE_REQUIRED, 'interface to listen on (0.0.0.0 for all)', '0.0.0.0');
            $this->addOption('port', 'p', InputOption::VALUE_REQUIRED, 'TCP port to listen on', 8000);

            $this->addArgument('path', InputArgument::OPTIONAL, 'The path to deploy to', getcwd());
        }
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new ConsoleIO($input, $output);
        $workDir = realpath($input->getArgument('path'));

        if (! $workDir) {
            $io->error('Path does not exist: ' . $input->getArgument('path'));
            return 1;
        }
        if (! is_readable($workDir)) {
            $io->error('Cannot read from path: ' . $workDir);
            return 1;
        }

        $requestHandler = $this->requestHandlerFactory->build($workDir);

        $uri = sprintf("%s:%s", $input->getOption('listen'), (int) $input->getOption('port'));

        $loop   = Factory::create();
        $socket = new SocketServer($uri, $loop);

        $server = new Server(function(ServerRequestInterface $request) use ($requestHandler, $io) {
            $io->log(sprintf('<info>> REQ</info>  %s', $request->getUri()->__toString()));
            $response = $requestHandler->handle($request);

            if ($response->getStatusCode() >= 400) {
                $io->log(sprintf(
                    '<fg=red>< RESP %s</fg=red> %s',
                    $response->getStatusCode(),
                    $response->getReasonPhrase()
                ));
            }
            else {
                $io->log(sprintf(
                    '<info>< RESP %s</info> %s (%s)',
                    $response->getStatusCode(),
                    $response->getReasonPhrase(),
                    $response->getBody()->getSize() !== null
                        ? \ByteUnits\bytes($response->getBody()->getSize())->format()
                        : 'streamed'
                ));
            }

            return $response;
        });

        $io->log(sprintf('Serving <info>%s</info> on <info>%s</info>', $workDir, $uri));

        $server->listen($socket);
        $loop->run();

        return 0;
    }
}