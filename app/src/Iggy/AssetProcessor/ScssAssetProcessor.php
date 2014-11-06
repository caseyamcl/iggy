<?php
/**
 * Created by PhpStorm.
 * User: casey
 * Date: 11/5/14
 * Time: 4:15 PM
 */

namespace Iggy\AssetProcessor;

use Leafo\ScssPhp\Compiler as ScssCompiler;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ScssAssetProcessor implements AssetProcessorInterface
{
    // Allow assets that are specified as directories
    use RecursiveDirParserTrait;

    // ----------------------------------------------------------------

    /**
     * @var ScssCompiler
     */
    private $scss;

    // ----------------------------------------------------------------

    /**
     * Constructor
     *
     * @param ScssCompiler $scss
     */
    public function __construct(ScssCompiler $scss)
    {
        $this->scss = $scss;
    }

    // ----------------------------------------------------------------

    /**
     * @param $path
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function load($path)
    {
        $parser = clone $this->scss;
        $files  = $this->getFileIterator($path);

        $streamer = function() use ($parser, $files) {
            foreach ($files as $file) {
                echo $parser->compile(file_get_contents($file));
            }
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
        return 'scss';
    }
}

/* EOF: ScssAssetProcessor.php */