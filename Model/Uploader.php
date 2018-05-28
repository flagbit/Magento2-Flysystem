<?php
namespace Flagbit\Flysystem\Model;

use Magento\Framework\File\Uploader as CoreUploader;

class Uploader extends CoreUploader
{
    public function __construct($fileId, $isFlysystem = false)
    {
        if(!$isFlysystem) {
            parent::__construct($fileId);
        }
    }

    public function setFile($file)
    {
        $this->_file = $file;
    }
}