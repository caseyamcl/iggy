<?php

namespace Iggy;

use RingCentral\Psr7\Response;

/**
 * Class HttpError
 * @package Iggy
 */
class HttpError extends \RuntimeException
{
    const AUTO = '';

    /**
     * @var array
     */
    private $data;

    /**
     * @param \Throwable $e
     * @return HttpError
     * @throws \ReflectionException
     */
    public static function fromThrowable(\Throwable $e)
    {
        return new static(500, $e->getMessage(), ['trace' => $e->getTrace()], $e);
    }

    /**
     * HttpError constructor
     *
     * @param int $code
     * @param string $message
     * @param array $data
     * @param \Throwable $prior
     * @throws \ReflectionException
     */
    public function __construct(int $code = 500, string $message = self::AUTO, array $data = [], \Throwable $prior = null)
    {
        $this->data = $data;
        parent::__construct($message ?: $this->autoMessage($code), $code, $prior);
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param int $code
     * @return string
     * @throws \ReflectionException
     */
    private function autoMessage(int $code): string
    {
        $phraseProp = new \ReflectionProperty(Response::class, 'phrases');
        $phraseProp->setAccessible(true);
        $phrases = $phraseProp->getValue();
        return $phrases[$code] ?? sprintf('An unknown error occurred (HTTP code: %s)', $code);
    }
}