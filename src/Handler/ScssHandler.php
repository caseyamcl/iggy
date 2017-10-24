<?php

namespace Iggy\Handler;
use Iggy\HandlerInterface;
use Leafo\ScssPhp\Compiler;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use React\Http\Response;

/**
 * Class ScssHandler
 * @package Iggy\Handler
 */
class ScssHandler implements HandlerInterface
{
    /**
     * @var Compiler
     */
    private $scss;

    /**
     * Constructor
     *
     * @param Compiler $scss
     */
    public function __construct(Compiler $scss = null)
    {
        $this->scss = $scss ?: new Compiler();
    }

    /**
     * Read a file and generate a HTTP response
     *
     * @param \SplFileInfo $file
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function handle(\SplFileInfo $file, RequestInterface $request)
    {
        $content = $this->scss->compile(file_get_contents($file->getRealPath()));
        return new Response(200, ['Content-Type' => 'text/css'], $content);
    }
}