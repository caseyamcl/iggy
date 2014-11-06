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

    /**
     * @dataProvider goodPathsProvider
     */
    public function testLoadReturnsSymfonyResponseForValidPath($path)
    {
        $obj = $this->getObject();
        $this->assertInstanceOf('\Symfony\Component\HttpFoundation\Response', $obj->load($path));
    }

    // ----------------------------------------------------------------

    /**
     * @dataProvider badPathsProvider
     */
    public function testLoadThrowsHttpExceptionForInvalidPath($path)
    {
        $this->setExpectedException('\RuntimeException');
        $obj = $this->getObject();
        $obj->load($path);
    }

    // ----------------------------------------------------------------

    /**
     * @return array  [['goodpath'], ['goodpath'], etc..]
     */
    abstract public function goodPathsProvider();

    // ----------------------------------------------------------------

    /**
     * @return array  [['badpath'], ['badpath'], etc..]
     */
    public function badPathsProvider()
    {
        return [
            [$this->getFixtureDir() . 'badPath' . rand(1000, 9999)] // Totally fake path
        ];
    }

    // ----------------------------------------------------------------

    /**
     * @return AssetProcessorInterface
     */
    abstract protected function getObject();

    // ----------------------------------------------------------------

    /**
     * @return string
     */
    protected function getFixtureDir()
    {
        return realpath(__DIR__ . '/../Fixtures/assets') . DIRECTORY_SEPARATOR;
    }
}

/* EOF: AbstractAssetProcessorTest.php */