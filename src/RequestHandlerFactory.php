<?php

namespace Iggy;

use Iggy\Twig\TwigFactory;

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

    /**
     * RequestHandlerFactory constructor.
     *
     * @param string|null $defaultTemplatesPath
     */
    public function __construct(string $defaultTemplatesPath = null)
    {
        $this->defaultTemplatesPath = $defaultTemplatesPath ?: __DIR__ . '/Resource/default_templates';
    }

    /**
     * @param string $contentPath
     * @return RequestHandler
     */
    public function build(string $contentPath): RequestHandler
    {
        $twigFactory = new TwigFactory(array_filter([
            is_readable($contentPath) ? $contentPath : null, // 1st
            $this->defaultTemplatesPath                      // 2nd
        ]));

        // Setup the file path resolver
        $fileResolver = new FilePathResolver($contentPath);

        // Setup the file handler
        $fileHandler = (new Handler\DecidingHandler())
            ->registerHandler(new Handler\TwigHandler($twigFactory, $contentPath), ['twig'])
            ->registerHandler(new Handler\LessHandler(), ['less'])
            ->registerHandler(new Handler\ScssHandler(), ['scss'])
            ->registerDefaultHandler(new Handler\FileHandler());

        $errorHandler = new ErrorHandler($twigFactory);
        return new RequestHandler($fileResolver, $fileHandler, $errorHandler);
    }
}