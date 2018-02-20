<?php

namespace Iggy;

use Symfony\Component\Console\Application;
use Webmozart\PathUtil\Path;
use Zend\Diactoros\Response\SapiEmitter;
use Zend\Diactoros\ServerRequestFactory;

/**
 * Class App
 * @package Iggy
 */
class App
{
    const VERSION = 1.0;

    /**
     * @var Application
     */
    private $consoleApp;

    /**
     * @var RequestHandlerFactory
     */
    private $requestHandlerFactory;

    /**
     * Run Console App
     */
    public static function console(): void
    {
        $that = new static();
        $that->consoleApp->run();
    }

    /**
     * Handle requests from an outside server (Apache, NGINX, etc.)
     *
     * @param string $contentDir The full system path to the content (defaults ./content)
     * @throws \ReflectionException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public static function request(string $contentDir = ''): void
    {
        $that = new static();

        // Handle the request
        $handler = $that->requestHandlerFactory->build($contentDir ?: Path::join(__DIR__, 'content'));
        $emitter = new SapiEmitter();

        // Build a request
        $request = ServerRequestFactory::fromGlobals();
        $response = $handler->handle($request);

        $emitter->emit($response);
        exit(0);
    }

    /**
     * App constructor.
     */
    public function __construct()
    {
        $this->requestHandlerFactory = new RequestHandlerFactory(__DIR__ . '/Resource/default_templates');

        $consoleApp = new Application('Iggy - The Lightweight PHP Twig Dev Environment', static::VERSION);
        $consoleApp->add(new Console\ServeCommand($this->requestHandlerFactory));
        $consoleApp->add(new Console\InitCommand(__DIR__ . '/Resource/skel'));
        $this->consoleApp = $consoleApp;
    }
}