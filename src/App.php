<?php

namespace Iggy;

use Iggy\Handler\DecidingHandler;
use Iggy\Handler\FileHandler;
use Iggy\Handler\LessHandler;
use Iggy\Handler\ScssHandler;
use Iggy\Handler\TwigHandler;
use Symfony\Component\Console\Application;
use Twig\Loader\FilesystemLoader;

/**
 * Class App
 * @package Iggy
 */
class App
{
    const AUTO    = '';
    const VERSION = 1.0;

    /**
     * @var Application
     */
    private $consoleApp;

    /**
     * @param string $baseDirectory
     */
    public static function main(string $baseDirectory = self::AUTO)
    {
        $that = new static($baseDirectory);
        $that->run();
    }

    /**
     * App constructor.
     *
     * @param string $baseDirectory
     */
    public function __construct(string $baseDirectory = self::AUTO)
    {
        $baseDirectory = $baseDirectory ?: getcwd();
        $requestHandler = $this->buildRequestHandler($baseDirectory);

        $consoleApp = new Application('Iggy - The Lightweight PHP Twig Dev Environment', static::VERSION);
        $consoleApp->add(new Console\ServeCommand($requestHandler));
        $consoleApp->add(new Console\InitCommand($baseDirectory, __DIR__ . '/Resource/skel'));
        $this->consoleApp = $consoleApp;
    }

    /**
     * Run the console app
     */
    public function run()
    {
        $this->consoleApp->run();
    }

    /**
     * Build the request handler
     *
     * @param string $baseDirectory
     * @return RequestHandler
     */
    protected function buildRequestHandler(string $baseDirectory): RequestHandler
    {
        $twigFactory = new TwigFactory(new FilesystemLoader([
            FilesystemLoader::MAIN_NAMESPACE => $baseDirectory,
            '@default_error_templates'       => __DIR__ . '/Resource/error_templates'
        ]));

        // Setup the file path resolver
        $fileResolver = new FilePathResolver($baseDirectory);

        // Setup the file handler
        $fileHandler = (new DecidingHandler())
            ->registerHandler(new TwigHandler($twigFactory, $baseDirectory), ['twig'])
            ->registerHandler(new LessHandler(), ['less'])
            ->registerHandler(new ScssHandler(), ['scss'])
            ->registerDefaultHandler(new FileHandler());

        $errorHandler = new ErrorHandler($twigFactory);

        return new RequestHandler($fileResolver, $fileHandler, $errorHandler);
    }
}