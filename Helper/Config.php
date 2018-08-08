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
    protected $_scopeConfig;

    /**
     * @var array Supported Filetypes in Flysystem Modals
     */
    protected $_supportedFileTypes = [
        'jpg',
        'jpeg',
        'png',
        'gif'
    ];

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

    const XPATH_CONFIG_SFTP_HOST = 'flagbit_flysystem/sftp/host';
    const XPATH_CONFIG_SFTP_PORT = 'flagbit_flysystem/sftp/port';
    const XPATH_CONFIG_SFTP_USERNAME = 'flagbit_flysystem/sftp/username';
    const XPATH_CONFIG_SFTP_PASSWORD = 'flagbit_flysystem/sftp/password';
    const XPATH_CONFIG_SFTP_PRIVATE_KEY_PATH_OR_CONTENT = 'flagbit_flysystem/sftp/private_key_path_or_content';
    const XPATH_CONFIG_SFTP_ROOT = 'flagbit_flysystem/sftp/root';
    const XPATH_CONFIG_SFTP_TIMEOUT = 'flagbit_flysystem/sftp/timeout';
    const XPATH_CONFIG_SFTP_DIRECTORY_PERMISSIONS = 'flagbit_flysystem/sftp/directory_permissions';

    const FLYSYSTEM_DIRECTORY = 'flagbit_flysystem';
    const FLYSYSTEM_DIRECTORY_THUMBS = '.thumbs';
    const FLYSYSTEM_DIRECTORY_TMP = '.tmp';
    const FLYSYSTEM_DIRECTORY_PREVIEW = '.preview';

    const FLYSYSTEM_UPLOAD_ID = 'upload_file';

    /**
     * Config constructor.
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->_scopeConfig = $scopeConfig;
    }

    /**
     * @return mixed
     */
    public function getSource()
    {
        return $this->_scopeConfig->getValue(self::XPATH_CONFIG_GENERAL_SOURCE);
    }

    /**
     * @return mixed
     */
    public function getLocalPath()
    {
        return $this->_scopeConfig->getValue(self::XPATH_CONFIG_LOCAL_PATH);
    }

    /**
     * @return int
     */
    public function getLocalLock()
    {
        return (int)$this->_scopeConfig->getValue(self::XPATH_CONFIG_LOCAL_LOCK);
    }

    /**
     * @return mixed
     */
    public function getFtpHost()
    {
        return $this->_scopeConfig->getValue(self::XPATH_CONFIG_FTP_HOST);
    }

    /**
     * @return mixed
     */
    public function getFtpUser()
    {
        return $this->_scopeConfig->getValue(self::XPATH_CONFIG_FTP_USER);
    }

    /**
     * @return mixed
     */
    public function getFtpPassword()
    {
        return $this->_scopeConfig->getValue(self::XPATH_CONFIG_FTP_PASSWORD);
    }

    /**
     * @return int
     */
    public function getFtpPort()
    {
        return (int)$this->_scopeConfig->getValue(self::XPATH_CONFIG_FTP_PORT);
    }

    /**
     * @return mixed
     */
    public function getFtpPath()
    {
        return $this->_scopeConfig->getValue(self::XPATH_CONFIG_FTP_PATH);
    }

    /**
     * @return bool
     */
    public function getFtpPassive()
    {
        return (bool)$this->_scopeConfig->getValue(self::XPATH_CONFIG_FTP_PASSIVE);
    }

    /**
     * @return bool
     */
    public function getFtpSsl()
    {
        return (bool)$this->_scopeConfig->getValue(self::XPATH_CONFIG_FTP_SSL);
    }

    /**
     * @return int
     */
    public function getFtpTimeout()
    {
        return (int)$this->_scopeConfig->getValue(self::XPATH_CONFIG_FTP_TIMEOUT);
    }

    /**
     * @return mixed
     */
    public function getSftpHost()
    {
        return $this->_scopeConfig->getValue(self::XPATH_CONFIG_SFTP_HOST);
    }

    /**
     * @return int
     */
    public function getSftpPort()
    {
        return (int)$this->_scopeConfig->getValue(self::XPATH_CONFIG_SFTP_PORT);
    }

    /**
     * @return mixed
     */
    public function getSftpUsername()
    {
        return $this->_scopeConfig->getValue(self::XPATH_CONFIG_SFTP_USERNAME);
    }

    /**
     * @return mixed
     */
    public function getSftpPassword()
    {
        return $this->_scopeConfig->getValue(self::XPATH_CONFIG_SFTP_PASSWORD);
    }

    /**
     * @return mixed
     */
    public function getSftpPrivateKeyPathOrContent()
    {
        return $this->_scopeConfig->getValue(self::XPATH_CONFIG_SFTP_PRIVATE_KEY_PATH_OR_CONTENT);
    }

    /**
     * @return mixed
     */
    public function getSftpRoot()
    {
        return $this->_scopeConfig->getValue(self::XPATH_CONFIG_SFTP_ROOT);
    }

    /**
     * @return mixed
     */
    public function getSftpTimeout()
    {
        return $this->_scopeConfig->getValue(self::XPATH_CONFIG_SFTP_TIMEOUT);
    }

    /**
     * @return int|null
     */
    public function getSftpDirectoryPermissions()
    {
        $directoryPermissions = $this->_scopeConfig->getValue(self::XPATH_CONFIG_SFTP_DIRECTORY_PERMISSIONS);

        if(empty($directoryPermissions)) return null;

        /**
         *  0 is used as base to let php decide how to convert the string value to an integer
         *  @see http://php.net/manual/de/function.intval.php,
         */
        return intval($directoryPermissions, 0);
    }

    /**
     * @return array
     */
    public function getSupportedFileTypes()
    {
        return $this->_supportedFileTypes;
    }
}