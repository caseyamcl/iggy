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