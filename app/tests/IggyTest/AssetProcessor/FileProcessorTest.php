<?php

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