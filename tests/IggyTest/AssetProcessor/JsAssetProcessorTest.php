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
use Iggy\AssetProcessor\JsAssetProcessor;

/**
 * Class JsAssetProcessorTest
 * @package IggyTest\AssetProcessor
 */
class JsAssetProcessorTest extends AbstractAssetProcessorTest
{
    public function testJsDirReturnsExpectedOutput()
    {
        $obj = $this->getObject();
        $path = $this->getFixtureDir(). 'js';

        $resp = $obj->load($path);

        ob_start();
        $resp->sendContent();
        $output = ob_get_contents();
        ob_end_clean();

        $this->assertContains('1234', $output);
        $this->assertContains('alert', $output);
    }

    /**
     * @return array  [['goodpath'], ['goodpath'], etc..]
     */
    public function goodPathsProvider()
    {
        return [
            [$this->getFixtureDir() . 'js/01-test.js'], // File
            [$this->getFixtureDir() . 'js/'] // Directory
        ];
    }

    /**
     * @return AssetProcessorInterface
     */
    protected function getObject()
    {
        return new JsAssetProcessor();
    }
}

/* EOF: JsAssetProcessorTest.php */ 