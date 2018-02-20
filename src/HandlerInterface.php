<?php

namespace Iggy;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class HandlerInterface
 * @package Iggy
 */
interface HandlerInterface
{
    /**
     * Read a file and generate a HTTP response
     *
     * @param \SplFileInfo $file
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(\SplFileInfo $file, ServerRequestInterface $request): ResponseInterface;
}