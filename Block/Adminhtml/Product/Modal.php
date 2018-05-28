<?php
namespace Flagbit\Flysystem\Block\Adminhtml\Product;

use Magento\Backend\Block\Template\Context;

class Modal extends \Magento\Backend\Block\Widget
{
    protected $_template = 'Flagbit_Flysystem::product/form/modal.phtml';

    public function __construct(
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    protected function _construct()
    {
        parent::_construct();
    }
}