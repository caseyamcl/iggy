<?php
/**
 * Created by PhpStorm.
 * User: casey
 * Date: 11/5/14
 * Time: 8:44 PM
 */

namespace IggyTest\AssetProcessor;

use Iggy\AssetProcessor\AssetProcessorInterface;
use Iggy\AssetProcessor\LessAssetProcessor;

/**
 * Class LessAssetProcessorTest
 * @package IggyTest\AssetProcessor
 */
class LessAssetProcessorTest extends AbstractAssetProcessorTest
{
    public function testLessDirReturnsExpectedOutput()
    {
        $path = $this->getFixtureDir() . 'less';
        $resp = $this->getObject()->load($path);

        ob_start();
        $resp->sendContent();
        $output = ob_get_contents();
        ob_end_clean();

        $this->assertContains("background-color: 'blue'", $output);
    }

    // ----------------------------------------------------------------

    /**
     * @return array  [['goodpath'], ['goodpath'], etc..]
     */
    public function goodPathsProvider()
    {
        return [
            [$this->getFixtureDir() . 'less/01-test.less'], // Test Single File
            [$this->getFixtureDir() . 'less']               // Test Dir
        ];
    }

    // ----------------------------------------------------------------

    /**
     * @return AssetProcessorInterface
     */
    protected function getObject()
    {
        return new LessAssetProcessor();
    }
}

/* EOF: LessAssetProcessorTest.php */ 