<?php

namespace Iggy;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

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
     * @return ErrorHandler
     */
    public function getErrorHandler(): ErrorHandler
    {
        return $this->errorHandler;
    }

    /**
     * Handle request
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \ReflectionException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
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
            error_log($e);
            return $this->errorHandler->handle($request, HttpError::fromThrowable($e));
        }
    }
}