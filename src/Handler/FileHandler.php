<?php

namespace Iggy\Handler;

use Iggy\HandlerInterface;
use Mimey\MimeTypes;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Response;
use RingCentral\Psr7\Stream;

/**
 * Class FileHandler
 * @package Iggy\Handler
 */
class FileHandler implements HandlerInterface
{
    /**
     * @var MimeTypes
     */
    private $mimeTypes;

    /**
     * FileHandler constructor.
     * @param MimeTypes|null $mimeTypes
     */
    public function __construct(MimeTypes $mimeTypes = null)
    {
        $this->mimeTypes = $mimeTypes ?: new MimeTypes();
    }

    /**
     * Build a response from a file
     *
     * @param \SplFileInfo $file
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(\SplFileInfo $file, ServerRequestInterface $request): ResponseInterface
    {
        $mimeType = $this->mimeTypes->getMimeType($file->getExtension()) ?: 'application/octet-stream';
        $stream = new Stream(fopen($file->getRealPath(), 'r'));
        return new Response(200, ['Content-type' => $mimeType], $stream);
    }
}