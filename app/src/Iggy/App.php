<?php

namespace Iggy;

use Iggy\AssetProcessor\AssetProcessorCollection;
use Iggy\AssetProcessor\JsAssetProcessor;
use Iggy\AssetProcessor\LessAssetProcessor;
use Iggy\AssetProcessor\ScssAssetProcessor;
use Iggy\Route\AssetRoute;
use Iggy\Route\PageRoute;
use Leafo\ScssPhp\Compiler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig_Loader_Filesystem;
use Twig_Environment;

/**
 * Iggy Iggy Templating/Theming Application
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 */
class App
{
    /**
     * @var string
     */
    private $basePath;

    /**
     * @var AssetProcessorCollection
     */
    private $assets;

    /**
     * @var ErrorHandler
     */
    private $errorHandler;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    // --------------------------------------------------------------

    /**
     * Static entry to application
     *
     * @param string|null $basePath
     */
    public static function main($basePath = null)
    {
        $that = new App($basePath);
        $that->run();
    }

    // --------------------------------------------------------------

    /**
     * Constructor
     *
     * @param string|null $basePath
     */
    public function __construct($basePath = null)
    {
        // Set the base path
        $this->basePath = realpath($basePath ?:
                dirname(filter_input(INPUT_SERVER, 'SCRIPT_FILENAME', FILTER_SANITIZE_STRING))
        );

        $this->twig         = $this->loadTwig($this->basePath . '/content');
        $this->assets       = $this->loadAssetProcessors();
        $this->errorHandler = new ErrorHandler($this->twig);
    }

    // --------------------------------------------------------------

    public function run()
    {
        //Get the Request
        $request = Request::createFromGlobals();

        try {
            // Add Twig Extension
            $this->twig->addExtension(new IggyTwigExtension($request, $this->assets));

            // Route the request and get the response
            $response = $this->routeRequest($request);
            $retCode = 0;
        }
        catch (\Exception $e) {
            $response = $this->handleException($e);
            $retCode = 1;
        }

        $response->send();
        exit($retCode);
    }

    // --------------------------------------------------------------

    /**
     * Load asset processors
     *
     * @return AssetProcessorCollection
     */
    protected function loadAssetProcessors()
    {
        return new AssetProcessorCollection([
            new LessAssetProcessor(new \Less_Parser()),
            new ScssAssetProcessor(new Compiler()),
            new JsAssetProcessor(new \JSqueeze())
        ]);
    }

    // ----------------------------------------------------------------

    /**
     * Route the request
     *
     * Really basic, janky router
     *
     * @param Request $request
     * @return Response
     */
    protected function routeRequest(Request $request)
    {
        $pathInfo = trim($request->getPathInfo(), '/');

        // If the first segment of the path is "_asset", route to asset
        if (substr($pathInfo, 0, strlen('_asset/')) == '_asset/') {
            $router = new AssetRoute($this->assets, $this->basePath . '/assets');
            $assetPathInfo = substr($pathInfo, strlen('_asset/'));
            $params = array_replace([null, null], explode('/', $assetPathInfo, 2));
            return call_user_func_array([$router, 'route'], $params);
        }

        // If first segment of the path is "_error", preview an error
        elseif (substr($pathInfo, 0, strlen('_error/')) == '_error/') {
            $errorName = substr($pathInfo, strlen('_error/'));
            throw new HttpException((int) $errorName ?: 500);
        }

        // Route to the page loader (default action)
        else {
            $router = new PageRoute($this->twig);
            return $router->handle($pathInfo, $request->query);
        }
    }

    // --------------------------------------------------------------

    protected function loadTwig($contentDir)
    {
        //Get Twig
        $loader = new Twig_Loader_Filesystem($contentDir);
        $twig = new Twig_Environment($loader);
        return $twig;
    }

    // ----------------------------------------------------------------

    protected function handleException(\Exception $e)
    {
        if ( ! $e instanceOf HttpException) {
            $e = new HttpException(500, $e->getMessage(), $e, $e->getCode());
        }

        return $this->errorHandler->handle($e);
    }
}

/* EOF: App.php */