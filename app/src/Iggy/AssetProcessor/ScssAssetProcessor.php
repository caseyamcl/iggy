<?php
/**
 * Created by PhpStorm.
 * User: casey
 * Date: 11/5/14
 * Time: 4:15 PM
 */

namespace Iggy\AssetProcessor;

use Leafo\ScssPhp\Compiler as ScssCompiler;
use Symfony\Component\HttpFoundation\Response;

/**
 * SCSS Asset Processor
 *
 * @package Iggy\AssetProcessor
 */
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
    public function __construct(ScssCompiler $scss = null)
    {
        $this->scss = $scss ?: new ScssCompiler();
    }

    // ----------------------------------------------------------------

    /**
     * @param $path
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function load($path)
    {
        $content = $this->scss->compile($this->getCombinedFiles($path));
        return new Response($content, 200, ['Content-type' => 'text/css']);
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