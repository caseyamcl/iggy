<?php
/**
 * Created by PhpStorm.
 * User: casey
 * Date: 11/5/14
 * Time: 9:41 PM
 */

namespace IggyTest\AssetProcessor;


use Iggy\AssetProcessor\AssetProcessorInterface;
use Iggy\AssetProcessor\ScssAssetProcessor;

/**
 * Class ScssAssetProcessorTest
 * @package IggyTest\AssetProcessor
 */
class ScssAssetProcessorTest extends AbstractAssetProcessorTest
{
    public function testScssDirReturnsExpectedOutput()
    {
        $path = $this->getFixtureDir() . 'scss';
        $resp = $this->getObject()->load($path);

        $this->assertContains("background-color: 'red'", $resp->getContent());
    }

    /**
     * @return array  [['goodpath'], ['goodpath'], etc..]
     */
    public function goodPathsProvider()
    {
        return [
            [$this->getFixtureDir() . 'scss/01-test.scss'], // Single file
            [$this->getFixtureDir() . 'scss'] // Directory
        ];
    }

    // ----------------------------------------------------------------

    /**
     * @return AssetProcessorInterface
     */
    protected function getObject()
    {
        return new ScssAssetProcessor();
    }
}

/* EOF: ScssAssetProcessorTest.php */ 