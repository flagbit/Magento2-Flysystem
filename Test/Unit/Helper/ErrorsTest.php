<?php
namespace Flagbit\Flysystem\Test\Unit\Helper;

use \Flagbit\Flysystem\Helper\Errors;
use \PHPUnit\Framework\TestCase;

class ErrorsTest extends TestCase
{
    public function testGetErrorMessage()
    {
        $messageId = 101;
        $expected = $messageId.': '.Errors::ERR_NO_101;

        $this->assertEquals($expected, Errors::getErrorMessage($messageId));
    }

    public function testGetErrorMessageParameters()
    {
        $messageId = 382;
        $params = ['jpg'];
        $expected = $messageId.': File type jpg is not allowed.';

        $this->assertEquals($expected, Errors::getErrorMessage($messageId, $params));
    }

    public function testGetErrorMessageUnknown()
    {
        $messageId = 9999;
        $expected = $messageId.': '.Errors::ERR_NO_000;

        $this->assertEquals($expected, Errors::getErrorMessage($messageId));
    }
}