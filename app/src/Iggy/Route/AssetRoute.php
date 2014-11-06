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

use Iggy\AssetProcessor\AssetProcessorCollection;
use Iggy\AssetProcessor\AssetProcessorException;
use Iggy\HttpException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Asset Route Handler
 *
 * @package Iggy\Route
 */
class AssetRoute
{
    /**
     * @var AssetProcessorCollection
     */
    private $assetProcessors;

    /**
     * @var string
     */
    private $assetBasePath;

    // ----------------------------------------------------------------

    /**
     * Constructor
     *
     * @param AssetProcessorCollection $assetProcessors
     * @param string $assetBasePath
     */
    public function __construct(AssetProcessorCollection $assetProcessors, $assetBasePath)
    {
        $this->assetProcessors = $assetProcessors;
        $this->assetBasePath = rtrim($assetBasePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }

    // ----------------------------------------------------------------

    /**
     * Handle the request
     *
     * Expects path to be '_asset/{type}/path...'
     *
     * @param string $type
     * @param string $path
     * @return Response
     */
    public function handle($type = null, $path = null)
    {
        if ( ! $type OR ! $path) {
            throw new HttpException(404, 'Invalid asset path');
        }

        if ( ! $this->assetProcessors->has($type)) {
            throw new HttpException(404, 'Invalid asset type');
        }

        try {
            return $this->assetProcessors->get($type)->load($this->assetBasePath . $path);
        }
        catch (AssetProcessorException $e) {
            throw new HttpException(404, 'Asset load error', $e, $e->getCode());
        }

    }
}

/* EOF: AssetRoute.php */ 