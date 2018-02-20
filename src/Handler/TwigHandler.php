<?php

namespace Iggy\Handler;

use Iggy\HandlerInterface;
use Iggy\Twig\TwigFactory;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use React\Http\Response;
use Webmozart\PathUtil\Path;

/**
 * Class TwigHandler
 * @package Iggy\Handler
 */
class TwigHandler implements HandlerInterface
{
    /**
     * @var string
     */
    private $basePath;

    /**
     * @var TwigFactory
     */
    private $twigFactory;

    /**
     * TwigHandler constructor.
     * @param TwigFactory $twigFactory
     * @param string $basePath
     * @internal param \Twig_Environment $twig
     */
    public function __construct(TwigFactory $twigFactory, $basePath)
    {
        $this->basePath    = $basePath;
        $this->twigFactory = $twigFactory;
    }

    /**
     * @param \SplFileInfo $file
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function handle(\SplFileInfo $file, RequestInterface $request)
    {
        $twig = $this->twigFactory->getTwigEnvironment($request);
        $relativePath = trim(Path::makeRelative($file->getRealPath(), $this->basePath), '/');
        return new Response(200, ['Content-Type' => 'text/html'], $twig->render($relativePath));
    }
}