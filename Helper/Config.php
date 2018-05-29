<?php
namespace Flagbit\Flysystem\Helper;

use \Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Class Config
 * @package Flagbit\Flysystem\Helper
 */
class Config
{
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    const XPATH_CONFIG_GENERAL_SOURCE = 'flagbit_flysystem/general/source';

    const XPATH_CONFIG_LOCAL_PATH = 'flagbit_flysystem/local/path';
    const XPATH_CONFIG_LOCAL_LOCK = 'flagbit_flysystem/local/lock';

    const XPATH_CONFIG_FTP_HOST = 'flagbit_flysystem/ftp/host';
    const XPATH_CONFIG_FTP_USER = 'flagbit_flysystem/ftp/user';
    const XPATH_CONFIG_FTP_PASSWORD = 'flagbit_flysystem/ftp/password';
    const XPATH_CONFIG_FTP_PORT = 'flagbit_flysystem/ftp/port';
    const XPATH_CONFIG_FTP_PATH = 'flagbit_flysystem/ftp/path';
    const XPATH_CONFIG_FTP_PASSIVE = 'flagbit_flysystem/ftp/passive';
    const XPATH_CONFIG_FTP_SSL = 'flagbit_flysystem/ftp/ssl';
    const XPATH_CONFIG_FTP_TIMEOUT = 'flagbit_flysystem/ftp/timeout';

    const FLYSYSTEM_DIRECTORY = 'flagbit_flysystem';
    const FLYSYSTEM_DIRECTORY_THUMBS = '.thumbs';
    const FLYSYSTEM_DIRECTORY_TMP = '.tmp';

    const FLYSYSTEM_UPLOAD_ID = 'upload_file';

    /**
     * Config constructor.
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return mixed
     */
    public function getSource()
    {
        return $this->scopeConfig->getValue(self::XPATH_CONFIG_GENERAL_SOURCE);
    }

    /**
     * @return mixed
     */
    public function getLocalPath()
    {
        return $this->scopeConfig->getValue(self::XPATH_CONFIG_LOCAL_PATH);
    }

    /**
     * @return int
     */
    public function getLocalLock()
    {
        return (int)$this->scopeConfig->getValue(self::XPATH_CONFIG_LOCAL_LOCK);
    }

    /**
     * @return mixed
     */
    public function getFtpHost()
    {
        return $this->scopeConfig->getValue(self::XPATH_CONFIG_FTP_HOST);
    }

    /**
     * @return mixed
     */
    public function getFtpUser()
    {
        return $this->scopeConfig->getValue(self::XPATH_CONFIG_FTP_USER);
    }

    /**
     * @return mixed
     */
    public function getFtpPassword()
    {
        return $this->scopeConfig->getValue(self::XPATH_CONFIG_FTP_PASSWORD);
    }

    /**
     * @return int
     */
    public function getFtpPort()
    {
        return (int)$this->scopeConfig->getValue(self::XPATH_CONFIG_FTP_PORT);
    }

    /**
     * @return mixed
     */
    public function getFtpPath()
    {
        return $this->scopeConfig->getValue(self::XPATH_CONFIG_FTP_PATH);
    }

    /**
     * @return bool
     */
    public function getFtpPassive()
    {
        return (bool)$this->scopeConfig->getValue(self::XPATH_CONFIG_FTP_PASSIVE);
    }

    /**
     * @return bool
     */
    public function getFtpSsl()
    {
        return (bool)$this->scopeConfig->getValue(self::XPATH_CONFIG_FTP_SSL);
    }

    /**
     * @return int
     */
    public function getFtpTimeout()
    {
        return (int)$this->scopeConfig->getValue(self::XPATH_CONFIG_FTP_TIMEOUT);
    }
}