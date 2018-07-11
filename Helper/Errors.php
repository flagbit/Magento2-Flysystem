<?php
namespace Flagbit\Flysystem\Helper;

use \Magento\Framework\App\Helper\AbstractHelper;

/**
 * Class Errors
 * @package Flagbit\Flysystem\Helper
 */
class Errors extends AbstractHelper
{
    /**
     * Error that is displayed when unkown Error number will be transfered
     */
    const ERR_NO_000 = "Unknown Error";

    /**
     * InitialConnection Errors
     */
    const ERR_NO_101 = "FTP connection failed. Please check your flysystem configuration.";
    const ERR_NO_111 = "Adapter could not be initialized. Please check your flysystem configuration.";

    /**
     * Errors while loading filesystem content
     */
    const ERR_NO_201 = "Invalid File Structure. Folder content cannot be shown";
    const ERR_NO_221 = " File Structure. Folder content cannot be shown";

    /**
     * Flysystem Errors while managing files
     */
    const ERR_NO_351 = "Deletion Request Failed. Please try again";
    const ERR_NO_381 = "Could not find %1 in tmp path. Upload failed.";
    const ERR_NO_382 = "File type %1 is not allowed.";

    /**
     * Magento File Errors (not Flysystem)
     */
    const ERR_NO_501 = "Uploaded File not found inside tmp folder.";

    /**
     * Development Errors while using Flysystem
     */
    const ERR_NO_621 = "Invalid Parameters in Observer %1 for event %2";

    /**
     * @param $num
     * @param array $params
     * @return \Magento\Framework\Phrase
     */
    static function getErrorMessage($num, $params = [])
    {

        $errorMsg = 'self::ERR_NO_' . $num;
        if(defined($errorMsg)) {
            $errorMsg = constant($errorMsg);
        } else {
            $errorMsg = self::ERR_NO_000;
        }

        return __('ERR '.$num.': '.$errorMsg, $params);
    }
}