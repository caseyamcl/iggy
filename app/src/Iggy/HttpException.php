<?php

/**
 * Iggy Rapid Prototyping App
 *
 * @license http://opensource.org/licenses/MIT
 * @link https://github.com/caseyamcl/iggy
 * @version 1.0
 * @package caseyamcl/iggy
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * ------------------------------------------------------------------
 */

namespace Iggy;

use Exception;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Http Exception
 *
 * @package Iggy
 */
class HttpException extends RuntimeException
{
    /**
     * @var int
     */
    private $httpCode;

    // ----------------------------------------------------------------

    /**
     * Constructor
     *
     * @param int $httpCode  HTTP code
     * @param null $message  Message (null for auto)
     * @param Exception $previous  Previous exception
     * @param null $code  Optional custom app-specific error code
     */
    public function __construct($httpCode = 500, $message = null, Exception $previous = null, $code = null)
    {
        parent::__construct(
            $message ?: $this->deriveMessage($httpCode),
            $code ?: $httpCode,
            $previous
        );

        $this->httpCode = $httpCode;
    }


    // ----------------------------------------------------------------

    /**
     * @return int
     */
    public function getHttpCode()
    {
        return $this->httpCode;
    }

    // ----------------------------------------------------------------

    /**
     * Derive error message
     *
     * @param int
     * $httpCode
     * @return string
     */
    protected function deriveMessage($httpCode)
    {
        return (isset(Response::$statusTexts[$httpCode]))
            ? Response::$statusTexts[$httpCode]
            : 'An error has occured';
    }

    // ----------------------------------------------------------------

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'httpCode'  => $this->getHttpCode(),
            'code'      => $this->getCode(),
            'message'   => $this->getMessage(),
            'exception' => $this
        ];
    }
}

/* EOF: HttpException.php */