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
namespace Iggy\Route;

use Iggy\HttpException;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Response;

/**
 * Page Route Handler
 *
 * @package Iggy\Route
 */
class PageRoute
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    // ----------------------------------------------------------------

    /**
     * Constructor
     *
     * @param \Twig_Environment $twig
     */
    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    // ----------------------------------------------------------------

    /**
     * @param string $path
     */
    public function handle($path, ParameterBag $queryParams)
    {
        // Any parameters passed into the query string are passed to the template
        $templateData = [
            'params' => $queryParams->all()
        ];

        // Paths to try
        $pathsToTry = [
            'pages/' . $path . '.html.twig',
            'pages/' . $path . '/index.html.twig'
        ];

        // Try each path to return a response with Twig content
        foreach ($pathsToTry as $twigPath) {
            try {
                $content = $this->twig->render($twigPath, $templateData);
                return new Response($content, 200, ['Content-type' => 'text/html']);
            }
            catch (\Twig_Error_Loader $e) {
                // pass on to next loop iteration..
            }
        }

        // If made it here, then give up
        throw new HttpException(404, "Page not found: " . $path);
    }
}

/* EOF: PageRoute.php */