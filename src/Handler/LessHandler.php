<?php

namespace Iggy\Handler;

use Iggy\HandlerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use React\Http\Response;

/**
 * Class LessHandler
 * @package Iggy\Handler
 */
class LessHandler implements HandlerInterface
{
    /**
     * @var \Less_Parser
     */
    private $lessParser;

    /**
     * LessHandler constructor.
     * @param \Less_Parser|null $lessParser
     */
    public function __construct(\Less_Parser $lessParser = null)
    {
        $this->lessParser = $lessParser ?: new \Less_Parser(['compressed' => true]);
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
        $parser = clone $this->lessParser;
        $parser->parseFile($file->getRealPath());
        return new Response(200, ['Content-Type' => 'text/css'], $parser->getCss());
    }
}