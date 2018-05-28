<?php
namespace Flagbit\Flysystem\Block\Adminhtml\Filesystem;

use \Magento\Backend\Block\Widget\Container;
use \Magento\Backend\Block\Widget\Context;
use \Magento\Framework\Serialize\Serializer\Json;

class Content extends Container
{
    protected $_jsonEncoder;

    public function __construct(
        Context $context,
        Json $jsonEncoder,
        array $data = []
    ) {
        $this->_jsonEncoder = $jsonEncoder;
        parent::__construct($context, $data);
    }

    protected function _construct()
    {
        parent::_construct();
        $this->_headerText = __('File Storage');
        $this->buttonList->remove('back');
        $this->buttonList->remove('edit');
    }

    public function getFilebrowserSetupObject()
    {
        $setupObject = [
            'newFolderPrompt' => __('New Folder Name:'),
            'deleteFolderConfirmationMessage' => __('Are you sure you want to delete this folder?'),
            'deleteFileConfirmationMessage' => __('Are you sure you want to delete this file?'),
            'targetElementId' => $this->getTargetElementId(),
            'contentsUrl' => $this->getContentsUrl(),
            'onInsertUrl' => $this->getOnInsertUrl(),
            'newFolderUrl' => '/',
            'deleteFolderUrl' => '/',
            'deleteFilesUrl' => '/',
            'headerText' => $this->getHeaderText(),
            'showBreadcrumbs' => true,
        ];

        return $this->_jsonEncoder->serialize($setupObject);
    }

    public function getContentsUrl()
    {
        return $this->getUrl('flagbit_flysystem/*/contents');
    }

    public function getOnInsertUrl()
    {
        return $this->getUrl('flagbit_flysystem/*/onInsert');
    }

    public function getTargetElementId()
    {
        return $this->getRequest()->getParam('target_element_id');
    }

    public function getModalIdentifier()
    {
        return $this->getRequest()->getParam('identifier');
    }
}