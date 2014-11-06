<?php
/**
 * Created by PhpStorm.
 * User: casey
 * Date: 11/5/14
 * Time: 3:57 PM
 */

namespace Iggy;


use Iggy\AssetProcessor\AssetProcessorCollection;
use Symfony\Component\HttpFoundation\Request;

/**
 * Adds Twig Stuff to Iggy
 *
 * @package Iggy
 */
class IggyTwigExtension extends \Twig_Extension
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var AssetProcessorCollection
     */
    private $assetProcessorCollection;

    // ----------------------------------------------------------------

    /**
     * Constructor
     *
     * @param Request $request
     * @param AssetProcessorCollection $assetProcessorCollection
     */
    public function __construct(Request $request, AssetProcessorCollection $assetProcessorCollection)
    {
        $this->request = $request;
        $this->assetProcessorCollection = $assetProcessorCollection;
    }

    // ----------------------------------------------------------------

    public function getGlobals()
    {
        $globals = parent::getGlobals();

        // Add site_url, base_url, and current_url
        $r =& $this->request;
        $globals['site_url']    = $r->getSchemeAndHttpHost() . $r->getBaseUrl();
        $globals['base_url']    = $r->getSchemeAndHttpHost() . $r->getBasePath();
        $globals['current_url'] = $r->getSchemeAndHttpHost() . $r->getBaseUrl() . $r->getPathInfo();

        return $globals;
    }

    // ----------------------------------------------------------------

    public function getFunctions()
    {
        $funcs = parent::getFunctions();

        // Add asset processor functions
        foreach ($this->assetProcessorCollection as $asset) {
            $funcs[] = new \Twig_SimpleFunction($asset->getSlug(), function($path) use ($asset) {
                return sprintf(
                    '%s%s/_asset/%s/%s',
                    $this->request->getSchemeAndHttpHost(),
                    $this->request->getBaseUrl(),
                    $asset->getSlug(),
                    $path
                );
            });
        }

        return $funcs;
    }

    // ----------------------------------------------------------------

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'iggy_twig_extension';
    }
}

/* EOF: IggyTwigExtension.php */