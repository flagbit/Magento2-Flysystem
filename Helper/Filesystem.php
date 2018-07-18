<?php
namespace Flagbit\Flysystem\Helper;

use \Magento\Framework\App\Helper\AbstractHelper;

/**
 * Class Config
 * @package Flagbit\Flysystem\Helper
 */
class Filesystem extends AbstractHelper
{
    /**
     * @var string
     */
    protected $_currentPath;

    /**
     * Ext Tree node key name
     *
     * @return string
     */
    public function getTreeNodeName()
    {
        return 'node';
    }

    /**
     * Encode string to valid HTML id element, based on base64 encoding
     *
     * @param string $string
     * @return string
     */
    public function idEncode($string)
    {
        return strtr(base64_encode($string), '+/=', ':_-');
    }


    /**
     * Revert opration to idEncode
     *
     * @param string $string
     * @return string
     */
    public function idDecode($string)
    {
        $string = strtr($string, ':_-', '+/=');
        return base64_decode($string);
    }

    /**
     * Return path of the current selected directory or root directory for startup
     * Try to create target directory if it doesn't exist
     *
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCurrentPath()
    {
        if (!$this->_currentPath) {
            $currentPath = '/';
            $path = $this->_getRequest()->getParam($this->getTreeNodeName());
            if ($path && $path !== 'root') {
                $currentPath = $this->idDecode($path);
            }
            $this->_currentPath = $currentPath;
        }
        return $this->_currentPath;
    }

    /**
     * Reduce filename by replacing some characters with dots
     *
     * @param string $filename
     * @param int $maxLength Maximum filename
     * @return string Truncated filename
     */
    public function getShortFilename($filename, $maxLength = 20)
    {
        $path = explode('/', $filename);
        $filename = $path[(count($path)-1)];

        if (strlen($filename) <= $maxLength) {
            return $filename;
        }
        return substr($filename, 0, $maxLength) . '...';
    }
}