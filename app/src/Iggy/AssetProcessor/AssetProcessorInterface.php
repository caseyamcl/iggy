<?php
/**
 * Created by PhpStorm.
 * User: casey
 * Date: 11/5/14
 * Time: 4:01 PM
 */

namespace Iggy\AssetProcessor;

/**
 * Interface AssetProcessorInterface
 *
 * @package Iggy
 */
interface AssetProcessorInterface
{
    /**
     * @param $path
     * @return \Symfony\Component\HttpFoundation\Response
     */
    function load($path);

    /**
     * Get the function name for use in template layer
     *
     * @return string
     */
    function getSlug();
}

/* EOF: AssetProcessorInterface.php */ 