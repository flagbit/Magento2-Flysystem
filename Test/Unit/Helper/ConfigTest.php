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

    public function testGetSftpHost()
    {
        $ftpHost = 'test.sftphost';

        $this->_scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(FlysystemConfig::XPATH_CONFIG_SFTP_HOST)
            ->willReturn($ftpHost);

        $this->assertEquals($ftpHost, $this->_object->getSftpHost());
    }

    public function testGetSftpPort()
    {
        $port = '22';
        $expected = 22;

        $this->_scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(FlysystemConfig::XPATH_CONFIG_SFTP_PORT)
            ->willReturn($port);

        $this->assertEquals($expected, $this->_object->getSftpPort());
    }

    public function testGetSftpUsername()
    {
        $sftpUsername = 'testUsername';

        $this->_scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(FlysystemConfig::XPATH_CONFIG_SFTP_USERNAME)
            ->willReturn($sftpUsername);

        $this->assertEquals($sftpUsername, $this->_object->getSftpUsername());
    }

    public function testGetSftpPassword()
    {
        $sftpPassword = 'testSftpPassword';

        $this->_scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(FlysystemConfig::XPATH_CONFIG_SFTP_PASSWORD)
            ->willReturn($sftpPassword);

        $this->assertEquals($sftpPassword, $this->_object->getSftpPassword());
    }

    public function testGetSftpPrivateKey()
    {
        $sftpPrivateKey = '/users/home/.ssh/private.key';

        $this->_scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(FlysystemConfig::XPATH_CONFIG_SFTP_PRIVATE_KEY_PATH_OR_CONTENT)
            ->willReturn($sftpPrivateKey);

        $this->assertEquals($sftpPrivateKey, $this->_object->getSftpPrivateKeyPathOrContent());
    }

    public function testGetSftpPrivateKeyPathOrContent()
    {
        $sftpPrivateKeyPath = '/users/home/.ssh/private.key';

        $this->_scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(FlysystemConfig::XPATH_CONFIG_SFTP_PRIVATE_KEY_PATH_OR_CONTENT)
            ->willReturn($sftpPrivateKeyPath);

        $this->assertEquals($sftpPrivateKeyPath, $this->_object->getSftpPrivateKeyPathOrContent());

        $sftpPrivateKeyContent = '-----BEGIN RSA PRIVATE KEY-----'.
            'MIICXAIBAAKBgQCqGKukO1De7zhZj6+H0qtjTkVxwTCpvKe4eCZ0FPqri0cb2JZfXJ/DgYSF6vUp'.
            'wmJG8wVQZKjeGcjDOL5UlsuusFncCzWBQ7RKNUSesmQRMSGkVb1/3j+skZ6UtW+5u09lHNsj6tQ5'.
            '1s1SPrCBkedbNf0Tp0GbMJDyR4e9T04ZZwIDAQABAoGAFijko56+qGyN8M0RVyaRAXz++xTqHBLh'.
            '3tx4VgMtrQ+WEgCjhoTwo23KMBAuJGSYnRmoBZM3lMfTKevIkAidPExvYCdm5dYq3XToLkkLv5L2'.
            'pIIVOFMDG+KESnAFV7l2c+cnzRMW0+b6f8mR1CJzZuxVLL6Q02fvLi55/mbSYxECQQDeAw6fiIQX'.
            'GukBI4eMZZt4nscy2o12KyYner3VpoeE+Np2q+Z3pvAMd/aNzQ/W9WaI+NRfcxUJrmfPwIGm63il'.
            'AkEAxCL5HQb2bQr4ByorcMWm/hEP2MZzROV73yF41hPsRC9m66KrheO9HPTJuo3/9s5p+sqGxOlF'.
            'L0NDt4SkosjgGwJAFklyR1uZ/wPJjj611cdBcztlPdqoxssQGnh85BzCj/u3WqBpE2vjvyyvyI5k'.
            'X6zk7S0ljKtt2jny2+00VsBerQJBAJGC1Mg5Oydo5NwD6BiROrPxGo2bpTbu/fhrT8ebHkTz2epl'.
            'U9VQQSQzY1oZMVX8i1m5WUTLPz2yLJIBQVdXqhMCQBGoiuSoSjafUhV7i1cEGpb88h5NBYZzWXGZ'.
            '37sJ5QsW+sJyoNde3xH8vdXhzU7eT82D6X/scw9RZz+/6rCJ4p0='.
            '-----END RSA PRIVATE KEY-----';

        $this->_scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(FlysystemConfig::XPATH_CONFIG_SFTP_PRIVATE_KEY_PATH_OR_CONTENT)
            ->willReturn($sftpPrivateKeyContent);

        $this->assertEquals($sftpPrivateKeyContent, $this->_object->getSftpPrivateKeyPathOrContent());
    }

    public function testGetSftpRoot()
    {
        $sftpRoot = '/sftp/root';

        $this->_scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(FlysystemConfig::XPATH_CONFIG_FTP_PATH)
            ->willReturn($sftpRoot);

        $this->assertEquals($sftpRoot, $this->_object->getSftpRoot());
    }

    public function testGetSftpTimeout()
    {
        $timeout = '40';
        $expected = 40;

        $this->_scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(FlysystemConfig::XPATH_CONFIG_SFTP_TIMEOUT)
            ->willReturn($timeout);

        $this->assertEquals($expected, $this->_object->getSftpTimeout());
    }

    public function testGetSftpDirectoryPermissions()
    {
        $directoryPermissions = '0755';
        $expected = 0755;

        $this->_scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(FlysystemConfig::XPATH_CONFIG_SFTP_TIMEOUT)
            ->willReturn($directoryPermissions);

        $this->assertEquals($expected, $this->_object->getSftpDirectoryPermissions());
    }

    public function testGetSupportedFileTypes()
    {
        $this->assertInternalType('array', $this->_object->getSupportedFileTypes());
    }
}