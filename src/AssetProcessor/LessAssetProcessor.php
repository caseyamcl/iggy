<?php
/**
 * Created by PhpStorm.
 * User: casey
 * Date: 11/5/14
 * Time: 4:02 PM
 */

namespace Iggy\AssetProcessor;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * LESS Asset Processor
 *
 * @package Iggy\AssetProcessor
 */
class LessAssetProcessor implements AssetProcessorInterface
{
    // Allow assets that are specified as directories
    use RecursiveDirParserTrait;

    // ----------------------------------------------------------------

    /**
     * @var \Less_Parser
     */
    private $lessParser;

    // ----------------------------------------------------------------

    /**
     * @param \Less_Parser $lessParser
     */
    public function __construct(\Less_Parser $lessParser = null)
    {
        $this->lessParser = $lessParser ?: new \Less_Parser(['compressed' => true]);
    }

    // ----------------------------------------------------------------

    /**
     * @param $path
     * @return \Symfony\Component\HttpFoundation\Response
     */
    function load($path)
    {
        $parser = clone $this->lessParser;

        $files = $this->getFileIterator($path);

        $streamer = function() use ($files, $parser) {
            foreach ($files as $file) {
                $parser->parseFile($file);
            }
            echo $parser->getCss();
        };

        return new StreamedResponse($streamer, 200, ['Content-type' => 'text/css']);
    }

    // ----------------------------------------------------------------

    /**
     * Get the function name for use in template layer
     *
     * @return string
     */
    function getSlug()
    {
        return 'less';
    }
}

/* EOF: LessAssetProcessor.php */