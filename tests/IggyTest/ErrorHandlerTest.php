<?php
/**
 * Created by PhpStorm.
 * User: casey
 * Date: 12/15/14
 * Time: 9:59 PM
 */

namespace IggyTest;

use Iggy\ErrorHandler;
use Iggy\HttpException;

/**
 * Class ErrorHandlerTest
 *
 * @package IggyTest
 */
class ErrorHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testInstantiation()
    {
        $obj = $this->getObject();
        $this->assertInstanceOf('\Iggy\ErrorHandler', $obj);
    }

    // ---------------------------------------------------------------

    public function testHandleUsesDefaultTemplateWhenTwigNotSet()
    {
        $obj = $this->getObject(false);
        $resp = $obj->handle(new HttpException());
        $this->assertContains("Iggy Error", $resp->getContent());
    }

    // ---------------------------------------------------------------

    public function testHandleUsesTwigTemplateWhenTwigIsSet()
    {
        $obj = $this->getObject(true);
        $resp = $obj->handle(new HttpException(404));

        $this->assertContains("rendered error page", $resp->getContent());
    }

    // ---------------------------------------------------------------

    public function testHandleUsesDefaultTemplateWhenTwigTemplateNotFound()
    {
        $obj = $this->getObject(true);
        $resp = $obj->handle(new HttpException(501));

        $this->assertNotContains("rendered error page", $resp->getContent());

    }

    // ---------------------------------------------------------------

    protected function getObject($loadTwig = true)
    {
        $twigMock = \Mockery::mock('\Twig_Environment');
        $twigMock->shouldReceive('render')->andReturnUsing(function($file, array $data) {
            switch ($file) {
                case 'errors/500.html.twig':
                case 'errors/404.html.twig':
                    return 'rendered error page';
                    break;
                default:
                    throw new \Twig_Error_Loader('Mock error');
            }
        });

        return $loadTwig ? new ErrorHandler($twigMock) : new ErrorHandler();
    }
}