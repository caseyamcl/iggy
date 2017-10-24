<?php

namespace Iggy\Handler;

use Iggy\HandlerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class DecidingHandler
 * @package Iggy\Handler
 */
class DecidingHandler implements HandlerInterface
{
    const DEFAULT_HANDLER_EXT = '*';

    /**
     * @var array|HandlerInterface  Key is file extension (sans dot) and value is Handler
     */
    private $handlerMap = [];

    /**
     * @param HandlerInterface $handler
     * @param array $fileExtensions
     * @return $this
     */
    public function registerHandler(HandlerInterface $handler, array $fileExtensions)
    {
        foreach ($fileExtensions as $fileExt) {
            if (array_key_exists($fileExt, $this->handlerMap)) {
                throw new \LogicException(sprintf(
                    'File extension cannot be registered: %s.  It is already registered for: %s',
                    $fileExt,
                    get_class($this->handlerMap[$fileExt])
                ));
            }

            $this->handlerMap[$fileExt] = $handler;
        }

        return $this;
    }

    /**
     * @param HandlerInterface $handler
     * @return $this
     */
    public function registerDefaultHandler(HandlerInterface $handler)
    {
        $this->registerHandler($handler, [self::DEFAULT_HANDLER_EXT]);
        return $this;
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
        $ext = trim(strtolower($file->getExtension()));
        if (array_key_exists($ext, $this->handlerMap)) {
            return $this->handlerMap[$ext]->handle($file, $request);
        }
        elseif (array_key_exists(self::DEFAULT_HANDLER_EXT, $this->handlerMap)) {
            return $this->handlerMap[self::DEFAULT_HANDLER_EXT]->handle($file, $request);
        }
        else {
            throw new \RuntimeException('Cannot find suitable handler (and no default handler) for: ' . $ext);
        }
    }
}