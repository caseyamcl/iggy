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
namespace IggyTest\Route;

use Iggy\Route\PageRoute;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Class PageRouteTest
 * @package IggyTest\Route
 */
class PageRouteTest extends \PHPUnit_Framework_TestCase
{
    public function testInstantiateSucceeds()
    {
        $obj = $this->getObject();
        $this->assertInstanceOf('\Iggy\Route\PageRoute', $obj);
    }

    // ----------------------------------------------------------------

    public function testHandleReturnsExpectedContent()
    {
        $obj = $this->getObject();

        $resp = $obj->handle('page', $this->getMockParameters());
        $this->assertContains('Aye', $resp->getContent());
    }

    // ----------------------------------------------------------------

    public function testHandleReturnsExpectedContentWhenPathIsDirWithIndexFile()
    {
        $obj = $this->getObject();
        $resp = $obj->handle('subpage', $this->getMockParameters());
        $this->assertContains('Hello page', $resp->getContent());
    }


    // ----------------------------------------------------------------

    public function testHandleReturnsExpectedContentWhenPathIsDirAndSubfile()
    {
        $obj = $this->getObject();
        $resp = $obj->handle('subpage/another', $this->getMockParameters());
        $this->assertContains('Hi There Another', $resp->getContent());
    }

    // ----------------------------------------------------------------

    public function testHandleThrowsHttpExcpetionForNonExistentPath()
    {
        $this->setExpectedException('\Iggy\HttpException');

        $obj = $this->getObject();
        $obj->handle('asdfbasd_doesnotexist', $this->getMockParameters());
    }

    // ----------------------------------------------------------------

    public function testHandleThrowsHttpExceptionForDirWithNoIndexPath()
    {
        $this->setExpectedException('\Iggy\HttpException');

        $obj = $this->getObject();
        $obj->handle('dironly', $this->getMockParameters());
    }

    // ----------------------------------------------------------------

    protected function getObject($filePath = null)
    {
        $path = $filePath ?: __DIR__ . '/../Fixtures/content';
        $twig = new \Twig_Environment(new \Twig_Loader_Filesystem($path));
        return new PageRoute($twig);
    }

    // ----------------------------------------------------------------

    protected function getMockParameters()
    {
        return new ParameterBag(['a' => 'Aye', 'b' => 'Bee']);
    }
}

/* EOF: PageRouteTest.php */ 