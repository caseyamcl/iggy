<?php

namespace Iggy\Console;
use Iggy\RequestHandler;
use Symfony\Component\Console\Command\Command;

/**
 * Class ServeCommand
 * @package Iggy\Console
 */
class ServeCommand extends Command
{
    /**
     * @var RequestHandler
     */
    private $requestHandler;

    /**
     * ServeCommand constructor.
     * @param RequestHandler $requestHandler
     */
    public function __construct(RequestHandler $requestHandler)
    {
        $this->requestHandler = $requestHandler;
        parent::__construct();
    }

    // TODO: Setup react server to handle requests
    // options:
    // - ip   (default 8000)
    // - port (default 172.0.0.1)
    // - path (default cwd)

    // Will use RequestHandler to handle request and return a response.
}