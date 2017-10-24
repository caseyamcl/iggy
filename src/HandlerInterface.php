<?php

namespace Iggy;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

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
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function handle(\SplFileInfo $file, RequestInterface $request);
}