<?php
namespace Flagbit\Flysystem\Test\Unit\Helper;

use \Flagbit\Flysystem\Helper\Errors;
use \PHPUnit\Framework\TestCase;

class ErrorsTest extends TestCase
{
    public function testGetErrorMessage(): void
    {
        $messageId = 101;
        $expected = 'ERR '.$messageId.': '.Errors::ERR_NO_101;

        $this->assertEquals($expected, Errors::getErrorMessage($messageId));
    }

    public function testGetErrorMessageParameters(): void
    {
        $messageId = 382;
        $params = ['jpg'];
        $expected = 'ERR '.$messageId.': File type jpg is not allowed.';

        $this->assertEquals($expected, Errors::getErrorMessage($messageId, $params));
    }

    public function testGetErrorMessageUnknown(): void
    {
        $messageId = 9999;
        $expected = 'ERR '.$messageId.': '.Errors::ERR_NO_000;

        $this->assertEquals($expected, Errors::getErrorMessage($messageId));
    }
}