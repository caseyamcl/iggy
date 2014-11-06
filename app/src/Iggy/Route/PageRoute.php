<?php
/**
 * Created by PhpStorm.
 * User: casey
 * Date: 11/5/14
 * Time: 4:31 PM
 */

namespace Iggy\Route;

use Iggy\HttpException;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Response;

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