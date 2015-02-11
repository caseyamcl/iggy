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