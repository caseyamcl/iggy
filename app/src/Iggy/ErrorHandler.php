<?php
/**
 * Created by PhpStorm.
 * User: casey
 * Date: 11/5/14
 * Time: 4:31 PM
 */

namespace Iggy;

use Symfony\Component\HttpFoundation\Response;


class ErrorHandler
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    // ----------------------------------------------------------------

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    // ----------------------------------------------------------------

    public function handle(HttpException $e)
    {
        // Try the application error code, then the HTTP error code, then just 'error'
        $templatesToTry = array(
            'error/' . $e->getcode() . '.html.twig',
            'error/' . $e->getHttpCode() . '.html.twig',
            'error/default.html.twig'
        );

        foreach ($templatesToTry as $file) {
            try {
                $content = $this->twig->render($file, $e->toArray());
                return new Response($content, $e->getHttpCode());
            }
            catch (\Twig_Error_Loader $e) {
                // pass to proceed in loop
            }
        }

        // If made it here, just use a default template
        return new Response($this->getDefaultHtmlTemplateForError($e), $e->getCode(), ['Content-type' => 'text/html']);
    }

    // ----------------------------------------------------------------

    /**
     * Get default error template
     * @param HttpException $e
     * @return string
     */
    private function getDefaultHtmlTemplateForError(HttpException $e)
    {
        $str = "
            <!doctype html>

            <html lang='en'>
            <head>
              <meta charset='utf-8'>

              <title>Iggy Error</title>
              <meta name='description' content='Default Iggy Error Page'>
            </head>

            <body>
                <h1>Iggy Error: {$e->getHttpCode()}</h1>
                <p>{$e->getMessage()}</p>
                <hr />
                <p>
                    Create a default error template ('content/error/error.html.twig')
                    to avoid seeing this default message.
                </p>
            </body>
            </html>
        ";

        return $str;
    }
}

/* EOF: ErrorHandler.php */