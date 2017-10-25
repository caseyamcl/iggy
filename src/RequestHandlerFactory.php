<?php

namespace Iggy;

use Twig\Loader\FilesystemLoader;

/**
 * Class RequestHandlerFactory
 * @package Iggy
 */
class RequestHandlerFactory
{
    /**
     * @var string
     */
    private $defaultTemplatesPath;

    public function __construct(string $defaultTemplatesPath)
    {
        $this->defaultTemplatesPath = $defaultTemplatesPath;
    }

    /**
     * @param string $basePath
     * @return RequestHandler
     */
    public function build(string $basePath): RequestHandler
    {
        $twigFactory = new TwigFactory(new FilesystemLoader(array_filter([
            is_readable($basePath) ? $basePath : null, // 1st
            $this->defaultTemplatesPath                // 2nd
        ])));

        // Setup the file path resolver
        $fileResolver = new FilePathResolver($basePath);

        // Setup the file handler
        $fileHandler = (new Handler\DecidingHandler())
            ->registerHandler(new Handler\TwigHandler($twigFactory, $basePath), ['twig'])
            ->registerHandler(new Handler\LessHandler(), ['less'])
            ->registerHandler(new Handler\ScssHandler(), ['scss'])
            ->registerDefaultHandler(new Handler\FileHandler());

        $errorHandler = new ErrorHandler($twigFactory);
        return new RequestHandler($fileResolver, $fileHandler, $errorHandler);
    }
}