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
namespace Iggy\Twig;

use Psr\Http\Message\RequestInterface;
use Twig\Extension\AbstractExtension;

/**
 * Adds Iggy functions and globals to Twig
 *
 * - Adds functions for generating urls for different types of assets
 * - Adds global variables
 *
 * @package Iggy
 */
class IggyTwigExtension extends AbstractExtension
{
    const HOME_PATH    = '';
    const CURRENT_PATH = '___CURRENT____';

    const PATH_ONLY = true;
    const FULL_URL  = false;

    /**
     * @var RequestInterface|null
     */
    private $request;

    /**
     * Constructor
     *
     * @param RequestInterface $request
     */
    public function __construct(RequestInterface $request = null)
    {
        if ($request) {
            $this->setRequest($request);
        }
    }

    public function setRequest(RequestInterface $request)
    {
        $this->request = $request;
    }


    public function getFunctions()
    {
        $funcs = parent::getFunctions();

        $funcs[] = new \Twig_SimpleFunction('current_path', function() {
            return $this->getUrl(self::PATH_ONLY, self::CURRENT_PATH);
        });

        $funcs[] = new \Twig_SimpleFunction('current_url', function() {
            return $this->getUrl(self::FULL_URL, self::CURRENT_PATH);
        });

        $funcs[] = new \Twig_SimpleFunction('path', function($path = '') {
            return $this->getUrl(self::PATH_ONLY, $path);
        });

        $funcs[] = new \Twig_SimpleFunction('url', function($path = '') {
            return $this->getUrl(self::FULL_URL, $path);
        });

        return $funcs;
    }

    /**
     * @param bool $pathOnly
     * @param string $sub
     * @return string
     */
    protected function getUrl($pathOnly = self::PATH_ONLY, $sub = self::HOME_PATH)
    {
        if (! $this->request) {
            throw new \LogicException(sprintf(
                "Cannot call %s::getUrl() without having setRequest()",
                get_called_class()
            ));
        }

        switch ($sub) {
            case self::CURRENT_PATH:
                $outPath = $this->request->getUri()->getPath();
                break;
            case self::HOME_PATH;
                $outPath = '/';
                break;
            default:
                $outPath = '/' . ltrim($sub, '/');
                break;
        }

        return $pathOnly
            ? $outPath
            : $this->request->getUri()->withPath($outPath)->__toString();
    }
}
