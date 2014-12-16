<?php
/**
 * Created by PhpStorm.
 * User: casey
 * Date: 12/15/14
 * Time: 10:29 PM
 */

namespace IggyTest;

use Iggy\HttpException;

/**
 * Class HttpExceptionTest
 *
 * @package IggyTest
 */
class HttpExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testInstantiateSucceeds()
    {
        $obj = new HttpException();
        $this->assertInstanceOf('\Iggy\HttpException', $obj);
    }

    // ---------------------------------------------------------------

    public function testGetMessageDerivesMessageWhenNoMessageSpecified()
    {
        $obj = new HttpException(401);
        $this->assertContains('Unauthorized', $obj->getMessage());
    }

    // ---------------------------------------------------------------

    public function testGetMessageReturnsGivenMessageWhenSpecified()
    {
        $obj = new HttpException(404, 'Could not find it');
        $this->assertEquals('Could not find it', $obj->getMessage());
    }

    // ---------------------------------------------------------------

    public function testToArrayReturnsExpectedValues()
    {
        $obj = new HttpException(501);
        $arr = $obj->toArray();

        $this->assertArrayHasKey('httpCode',  $arr);
        $this->assertArrayHasKey('code',      $arr);
        $this->assertArrayHasKey('message',   $arr);
        $this->assertArrayHasKey('exception', $arr);
    }
}
