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
    // TODO: Make this configurable
    const DISALLOWED_PATTERNS = ['/^_templates/'];

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
            // Check diallowed URLs
            if ($this->hasDisallowedUrl($request)) {
                return $this->errorHandler->handle($request, new HttpError(403, 'Forbidden path'));
            }

            // If path can be resolved to a file, then handle it
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

    /**
     * Check if disallowed URL
     *
     * @param ServerRequestInterface $request
     * @return bool
     */
    private function hasDisallowedUrl(ServerRequestInterface $request): bool
    {
        $path = '/' . ltrim($request->getUri()->getPath(), '/');

        foreach (static::DISALLOWED_PATTERNS as $pattern) {
            if (preg_match($pattern, $path)) {
                return true;
            }
        }

        return false;
    }
}