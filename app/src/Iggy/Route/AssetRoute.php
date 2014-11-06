<?php
/**
 * Created by PhpStorm.
 * User: casey
 * Date: 11/5/14
 * Time: 4:31 PM
 */

namespace Iggy\Route;

use Iggy\AssetProcessor\AssetProcessorCollection;
use Iggy\HttpException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Asset Route
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
    private $assetPath;

    // ----------------------------------------------------------------

    /**
     * Constructor
     *
     * @param AssetProcessorCollection $assetProcessors
     * @param string $assetPath
     */
    public function __construct(AssetProcessorCollection $assetProcessors, $assetPath)
    {
        $this->assetProcessors = $assetProcessors;
        $this->assetPath = rtrim($assetPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
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

        return $this->assetProcessors->get($type)->load($this->assetPath . $path);
    }
}

/* EOF: AssetRoute.php */ 