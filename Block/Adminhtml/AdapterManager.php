<?php
namespace Flagbit\Flysystem\Block\Adminhtml;

use \Flagbit\Flysystem\Helper\Config;
use \Magento\Backend\Block\Widget\Button\SplitButton;
use \Magento\Backend\Block\Widget\Container;
use \Magento\Backend\Block\Widget\Context;

class AdapterManager extends Container
{
    /**
     * @var Config
     */
    protected $_flysystemConfig;

    /**
     * AdapterManager constructor.
     * @param Config $flysystemConfig
     * @param Context $context
     * @param array $data
     */
    public function  __construct(
        Config $flysystemConfig,
        Context $context,
        array $data = []
    ) {
        $this->_flysystemConfig = $flysystemConfig;
        parent::__construct($context, $data);
    }

    /**
     * Prepare adapter options button
     *
     * @return AdapterManager|Container
     */
    protected function _prepareLayout()
    {
        $addButtonProps = [
            'id' => 'flagbit_flysystem_new_adapter',
            'label' => __('Add Adapter'),
            'class' => 'add',
            'button_class' => '',
            'class_name' => SplitButton::class,
            'options' => $this->_getAddAdapterOptions(),
        ];
        $this->buttonList->add('add_new', $addButtonProps);

        return parent::_prepareLayout();
    }

    /**
     * Retrieve options for 'Add Product' split button
     *
     * @return array
     */
    protected function _getAddAdapterOptions()
    {
        $splitButtonOptions = [];
        $types = $this->_flysystemConfig->getAdapterList();

        foreach ($types as $type) {
            $splitButtonOptions[$type['identifier']] = [
                'label' => __($type['title']),
                'onclick' => "setLocation('" . $this->_getAdapterCreateUrl($type['identifier']) . "')",
                'default' => 'local',
            ];
        }

        return $splitButtonOptions;
    }

    protected function _getAdapterCreateUrl($identifier): string
    {
        return $this->getUrl(
            '*/*/edit',
            ['type' => $identifier]
        );
    }
}
