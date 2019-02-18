<?php
namespace Flagbit\Flysystem\Block\Adminhtml\Product;

use \Magento\Backend\Block\Template\Context;

/**
 * Class Modal
 * @package Flagbit\Flysystem\Block\Adminhtml\Product
 */
class Modal extends \Magento\Backend\Block\Widget
{
    /**
     * @var string
     */
    protected $_template = 'Flagbit_Flysystem::product/form/modal.phtml';

    /**
     * Modal constructor.
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    protected function _construct(): void
    {
        parent::_construct();
    }
}