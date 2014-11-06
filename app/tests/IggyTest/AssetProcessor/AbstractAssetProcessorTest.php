<?php

use Iggy\AssetProcessor\AssetProcessorInterface;

/**
 * Created by PhpStorm.
 * User: casey
 * Date: 11/5/14
 * Time: 8:25 PM
 */

abstract class AbstractAssetProcessorTest extends \PHPUnit_Framework_TestCase
{
    public function testInstatiate()
    {
        $obj = $this->getObject();
        $this->assertInstanceOf('\Iggy\AssetProcessor\AssetProcessorInterface', $obj);
    }

    // ----------------------------------------------------------------

    public function testGetSlugReturnsNonEmptyString()
    {
        $obj = $this->getObject();
        $this->assertInternalType('string', $obj->getSlug());
        $this->assertNotEmpty($obj->getSlug());
    }

    // ----------------------------------------------------------------

    public function testLoadReturnsSymfonyResponseForValidPath()
    {

    }

    // ----------------------------------------------------------------

    public function testLoadThrowsHttpExceptionForInvalidPath()
    {
        //
    }

    // ----------------------------------------------------------------

    /**
     * @return AssetProcessorInterface
     */
    abstract protected function getObject();

    // ----------------------------------------------------------------

}

/* EOF: AbstractAssetProcessorTest.php */