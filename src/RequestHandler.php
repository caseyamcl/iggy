<?php

namespace Iggy;

use Iggy\Handler\FileHandler;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class RequestHandler
 * @package Iggy
 */
class RequestHandler
{
    /**
     * @var FilePathResolver
     */
    private $fileResolver;

    /**
     * @var HandlerInterface
     */
    private $fileHandler;

    /**
     * @var ErrorHandler
     */
    private $errorHandler;

    /**
     * RequestHandler constructor.
     *
     * @param FilePathResolver $filePathResolver
     * @param HandlerInterface $handler
     * @param ErrorHandler $errorHandler
     */
    public function __construct(
        FilePathResolver $filePathResolver,
        HandlerInterface $handler,
        ErrorHandler $errorHandler
    ) {
        $this->fileResolver = $filePathResolver;
        $this->fileHandler = $handler;
        $this->errorHandler = $errorHandler;
    }

    /**
     * Handle request
     *
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function handle(RequestInterface $request): ResponseInterface
    {
        try {
            // If path can be resolved to a file, then
            if ($fileInfo = $this->fileResolver->resolvePath($request->getUri()->getPath())) {
                return $this->fileHandler->handle($fileInfo, $request);
            }
            else {
                return $this->errorHandler->handle(
                    $request,
                    new HttpError(404, 'Path not found: '. $request->getUri()->getPath())
                );
            }
        }
        catch (\Throwable $e) {
            return $this->errorHandler->handle($request, HttpError::fromThrowable($e));
        }
    }
}