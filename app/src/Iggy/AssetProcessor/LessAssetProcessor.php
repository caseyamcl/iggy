<?php
/**
 * Created by PhpStorm.
 * User: casey
 * Date: 11/5/14
 * Time: 4:02 PM
 */

namespace Iggy\AssetProcessor;


use Symfony\Component\HttpFoundation\StreamedResponse;

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
    public function __construct(\Less_Parser $lessParser)
    {
        $this->lessParser = $lessParser;
    }

    // ----------------------------------------------------------------

    /**
     * @param $path
     * @return \Symfony\Component\HttpFoundation\Response
     */
    function load($path)
    {
        $parser = clone $this->lessParser;

        foreach ($this->getFileIterator($path) as $file) {
            $parser->parseFile($file);
        }

        $streamer = function() use ($parser) {
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