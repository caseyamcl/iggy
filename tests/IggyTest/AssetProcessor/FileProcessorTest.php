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
use Iggy\AssetProcessor\FileProcessor;

/**
 * Class LessAssetProcessorTest
 * @package IggyTest\AssetProcessor
 */
class FileProcessorTest extends AbstractAssetProcessorTest
{
    public function testImageReturnsCorrectMimeType()
    {
        $obj = $this->getObject();
        $resp = $obj->load($this->getFixtureDir() . 'img/whippet.jpg');

        $this->assertEquals('image/jpeg', $resp->headers->get('content-type'));
    }

    // ----------------------------------------------------------------

    /**
     * @return array  [['goodpath'], ['goodpath'], etc..]
     */
    public function goodPathsProvider()
    {
        return [
            [$this->getFixtureDir() . 'js/01-test.js'],  // Test Single File
            [$this->getFixtureDir() . 'img/whippet.jpg'] // Test Dir
        ];
    }

    // ----------------------------------------------------------------

    /**
     * @return AssetProcessorInterface
     */
    protected function getObject()
    {
        return new FileProcessor();
    }
}

/* EOF: LessAssetProcessorTest.php */