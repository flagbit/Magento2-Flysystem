<?php
namespace Flagbit\Flysystem\Block\Adminhtml\Filesystem;

use \Magento\Backend\Block\Template;
use \Magento\Backend\Block\Template\Context;

class Preview extends Template
{
    public function __construct(
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    public function getPreviewUrl()
    {
        return $this->getUrl('flagbit_flysystem/*/preview');
    }
}