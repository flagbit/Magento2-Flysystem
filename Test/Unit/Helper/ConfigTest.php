<?php
namespace Flagbit\Flysystem\Test\Unit\Helper;

use \Flagbit\Flysystem\Helper\Config as FlysystemConfig;
use \Magento\Framework\App\Config;
use \PHPUnit\Framework\MockObject\MockObject;
use \PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    /**
     * @var Config|MockObject
     */
    protected $_scopeConfigMock;

    /**
     * @var FlysystemConfig
     */
    protected $_object;

    public function setUp()
    {
        $this->_scopeConfigMock = $this->getMockBuilder(Config::class)
            ->disableOriginalConstructor()
            ->setMethods(['getValue'])
            ->getMock();

        $this->_object = new FlysystemConfig(
            $this->_scopeConfigMock
        );
    }

    public function testGetSource()
    {
        $value = 'local';

        $this->_scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(FlysystemConfig::XPATH_CONFIG_GENERAL_SOURCE)
            ->willReturn($value);

        $this->assertEquals($value, $this->_object->getSource());
    }

    public function testGetLocalPath()
    {
        $path = '/test/path';

        $this->_scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(FlysystemConfig::XPATH_CONFIG_LOCAL_PATH)
            ->willReturn($path);

        $this->assertEquals($path, $this->_object->getLocalPath());
    }

    public function testGetLocalLock()
    {
        $lock = '1';
        $expected = 1;

        $this->_scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(FlysystemConfig::XPATH_CONFIG_LOCAL_LOCK)
            ->willReturn($lock);

        $this->assertEquals($expected, $this->_object->getLocalLock());
    }

    public function testGetFtpHost()
    {
        $ftpHost = 'test.host';

        $this->_scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(FlysystemConfig::XPATH_CONFIG_FTP_HOST)
            ->willReturn($ftpHost);

        $this->assertEquals($ftpHost, $this->_object->getFtpHost());
    }

    public function testGetFtpUser()
    {
        $ftpUser = 'test';

        $this->_scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(FlysystemConfig::XPATH_CONFIG_FTP_USER)
            ->willReturn($ftpUser);

        $this->assertEquals($ftpUser, $this->_object->getFtpUser());
    }

    public function testGetFtpPassword()
    {
        $ftpPassword = 'test';

        $this->_scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(FlysystemConfig::XPATH_CONFIG_FTP_PASSWORD)
            ->willReturn($ftpPassword);

        $this->assertEquals($ftpPassword, $this->_object->getFtpPassword());
    }

    public function testGetFtpPort()
    {
        $port = '20';
        $expected = 20;

        $this->_scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(FlysystemConfig::XPATH_CONFIG_FTP_PORT)
            ->willReturn($port);

        $this->assertEquals($expected, $this->_object->getFtpPort());
    }

    public function testGetFtpPath()
    {
        $ftpPath = '/test/path';

        $this->_scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(FlysystemConfig::XPATH_CONFIG_FTP_PATH)
            ->willReturn($ftpPath);

        $this->assertEquals($ftpPath, $this->_object->getFtpPath());
    }

    public function testGetFtpPassive()
    {
        $passive = '0';
        $expected = false;

        $this->_scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(FlysystemConfig::XPATH_CONFIG_FTP_PASSIVE)
            ->willReturn($passive);

        $this->assertEquals($expected, $this->_object->getFtpPassive());
    }

    public function testGetFtpSsl()
    {
        $ssl = 1;
        $expected = true;

        $this->_scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(FlysystemConfig::XPATH_CONFIG_FTP_SSL)
            ->willReturn($ssl);

        $this->assertEquals($expected, $this->_object->getFtpSsl());
    }

    public function testGetFtpTimeout()
    {
        $timeout = '30';
        $expected = 30;

        $this->_scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(FlysystemConfig::XPATH_CONFIG_FTP_TIMEOUT)
            ->willReturn($timeout);

        $this->assertEquals($expected, $this->_object->getFtpTimeout());
    }

    public function testGetSupportedFileTypes()
    {
        $this->assertInternalType('array', $this->_object->getSupportedFileTypes());
    }
}