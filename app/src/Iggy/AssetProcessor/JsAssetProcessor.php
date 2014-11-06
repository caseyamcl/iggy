<?php
/**
 * Created by PhpStorm.
 * User: casey
 * Date: 11/5/14
 * Time: 4:20 PM
 */

namespace Iggy\AssetProcessor;

use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * JS Asset Processor
 *
 * @package Iggy\AssetProcessor
 */
class JsAssetProcessor implements AssetProcessorInterface
{
    // Allow assets that are specified as directories
    use RecursiveDirParserTrait;

    // ----------------------------------------------------------------

    /**
     * @var \JSqueeze
     */
    private $jSqueeze;

    // ----------------------------------------------------------------

    /**
     * Constructor
     *
     * @param \JSqueeze $jSqueeze
     */
    public function __construct(\JSqueeze $jSqueeze)
    {
        $this->jSqueeze = $jSqueeze;
    }

    // ----------------------------------------------------------------

    /**
     * @param $path
     * @return \Symfony\Component\HttpFoundation\Response
     */
    function load($path)
    {
        $parser = clone $this->jSqueeze;
        $files  = $this->getFileIterator($path);

        $streamer = function() use ($parser, $files) {
            foreach ($files as $file) {
                echo $parser->squeeze(file_get_contents($file));
            }
        };

        return new StreamedResponse($streamer, 200, ['Content-type' => 'text/css']);    }

    // ----------------------------------------------------------------

    /**
     * Get the function name for use in template layer
     *
     * @return string
     */
    function getSlug()
    {
        return 'js';
    }
}

/* EOF: JsAssetProcessor.php */ 