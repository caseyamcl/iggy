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

use Iggy\AssetProcessor\AssetProcessorCollection;
use Symfony\Component\HttpFoundation\Request;

/**
 * Adds Iggy functions and globals to Twig
 *
 * - Adds functions for generating urls for different types of assets
 * - Adds global variables
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

        // Add site_url() and base_url() methods
        $funcs[] = new \Twig_SimpleFunction('site_url', function($subPath = null) {
            $siteUrl = $this->request->getSchemeAndHttpHost() . $this->request->getBaseUrl();
            if ($subPath) {
                $siteUrl .= '/' . ltrim($subPath, '/');
            }
            return $siteUrl;
        });

        $funcs[] = new \Twig_SimpleFunction('base_url', function($subPath = null) {
            $siteUrl = $this->request->getSchemeAndHttpHost() . $this->request->getBasePath();
            if ($subPath) {
                $siteUrl .= '/' . ltrim($subPath, '/');
            }
            return $siteUrl;
        });

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