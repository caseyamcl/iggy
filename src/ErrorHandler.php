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

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use React\Http\Response;

/**
 * Iggy Error Handler
 *
 * Attempt to load custom error page, but falls back to valid HTML default
 *
 * @package Iggy
 */
class ErrorHandler
{
    /**
     * @var TwigFactory
     */
    private $twigFactory;

    /**
     * Constructor
     *
     * @param TwigFactory $twigFactory
     */
    public function __construct(TwigFactory $twigFactory)
    {
        $this->twigFactory = $twigFactory;
    }

    /**
     * @param RequestInterface $request
     * @param HttpError $error
     * @return ResponseInterface
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function handle(RequestInterface $request, HttpError $error): ResponseInterface
    {
        if ($content = $this->renderTwigError($request, $error)) {
            return new Response($error->getCode(), ['Content-type' => 'text/html'], $content);
        }
        else {

            $message = trim(sprintf(
                "%s (Code: %s)\r\n\r\n%s",
                $error->getMessage(),
                $error->getCode(),
                (! empty($error->getData())) ? "Data: " . json_encode($error->getData()) : ''
            ));

            return new Response($error->getCode(), ['Content-type' => 'text/plain'], $message);
        }
    }

    /**
     * Get Twig Template for Error
     *
     * @param RequestInterface $request
     * @param HttpError $error
     * @return null|string Rendered template (NULL if rendering failed)
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    protected function renderTwigError(RequestInterface $request, HttpError $error): ?string
    {
        $twig = $this->twigFactory->getTwigEnvironment($request);

        // Try the application error code, then the
        // HTTP error code, then just 'error'
        $templatesToTry = array(
            'errors/' . $error->getCode() . '.twig',
            'errors/default.twig',
            '_templates/' . $error->getCode() . '.error.twig',
            '_templates/default.error.twig',
        );

        foreach ($templatesToTry as $file) {
            try {
                return $twig->render($file, ['error' => $error]);
            }
            catch (\Twig_Error_Loader $er) {
                // pass to proceed in loop
            }
        }

        return '';
    }
}
