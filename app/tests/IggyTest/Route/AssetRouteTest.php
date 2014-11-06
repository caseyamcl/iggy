<?php
/**
 * Created by PhpStorm.
 * User: casey
 * Date: 11/5/14
 * Time: 9:51 PM
 */

namespace IggyTest\Route;

use Iggy\AssetProcessor\AssetProcessorCollection;
use Iggy\AssetProcessor\AssetProcessorException;
use Iggy\AssetProcessor\LessAssetProcessor;
use Iggy\AssetProcessor\ScssAssetProcessor;
use Iggy\Route\AssetRoute;

/**
 * Class AssetRouteTest
 * @package IggyTest\Route
 */
class AssetRouteTest extends \PHPUnit_Framework_TestCase
{
    public function testInstantiateSucceeds()
    {
        $obj = $this->getObject();
        $this->assertInstanceOf('\Iggy\Route\AssetRoute', $obj);
    }

    // ----------------------------------------------------------------

    /**
     * @param string $type
     * @param string $path
     * @dataProvider goodAssetsDataProvider
     */
    public function testGetExistingAssetReturnsSymfonyResponse($type, $path)
    {
        $obj = $this->getObject();
        $resp = $obj->handle($type, $path);

        $this->assertInstanceOf('\Symfony\Component\HttpFoundation\Response', $resp);
    }

    // ----------------------------------------------------------------

    public function goodAssetsDataProvider()
    {
        return [
            ['less', 'less/01-test.less'],
            ['less', 'less/']
        ];
    }

    // ----------------------------------------------------------------

    public function testNonExistentTypeThrowsHttpException()
    {
        $this->setExpectedException('\Iggy\HttpException');

        $obj = $this->getObject();
        $obj->handle('foobar', 'less/01-test.less');
    }

    // ----------------------------------------------------------------

    public function testNonExistentPathThrowsHttpException()
    {
        $this->setExpectedException('\Iggy\HttpException');

        $obj = $this->getObject();
        $obj->handle('less', 'does/not/exist');
    }

    // ----------------------------------------------------------------

    protected function getObject()
    {
        $assetProcessorCollection = new AssetProcessorCollection([
            new LessAssetProcessor(),
            new ScssAssetProcessor()
        ]);

        return new AssetRoute($assetProcessorCollection, __DIR__ . '/../Fixtures/assets');
    }
}

/* EOF: AssetRouteTest.php */ 