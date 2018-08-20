<?php
namespace Flagbit\Flysystem\Block\Adminhtml\Filesystem;

use \Magento\Backend\Block\Widget\Container;
use \Magento\Backend\Block\Widget\Context;
use \Magento\Framework\Serialize\Serializer\Json;

/**
 * Class Content
 * @package Flagbit\Flysystem\Block\Adminhtml\Filesystem
 */
class Content extends Container
{
    /**
     * @var Json
     */
    protected $_jsonEncoder;

    /**
     * Content constructor.
     * @param Context $context
     * @param Json $jsonEncoder
     * @param array $data
     */
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

        if($this->getModalIdentifier() === 'flagbit_cms_modal') {
            $this->buttonList->add(
                'open_wysiwyg_browser',
                ['id' => 'open-wysiwyg-btn', 'label' => __('Open Default Browser'), 'type' => 'button'],
                0,
                0,
                'header'
            );
        }

        if($this->_authorization->isAllowed('Flagbit_Flysystem::folder_create')) {
            $this->buttonList->add(
                'new_folder',
                ['class' => 'save', 'label' => __('Create Folder...'), 'type' => 'button'],
                0,
                0,
                'header'
            );
        }

        $this->buttonList->add(
            'preview_file_btn',
            ['id' => 'preview-file-btn', 'label' => __('Preview File'), 'type' => 'button'],
            0,
            0,
            'header'
        );

        if($this->_authorization->isAllowed('Flagbit_Flysystem::folder_delete')) {
            $this->buttonList->add(
                'delete_folder',
                ['class' => 'delete no-display', 'label' => __('Delete Folder'), 'type' => 'button'],
                0,
                0,
                'header'
            );
        }

        if($this->_authorization->isAllowed('Flagbit_Flysystem::file_delete')) {
            $this->buttonList->add(
                'delete_files',
                ['class' => 'delete no-display', 'label' => __('Delete File'), 'type' => 'button'],
                0,
                0,
                'header'
            );
        }

        if($this->_authorization->isAllowed('Flagbit_Flysystem::file_insert')) {
            $this->buttonList->add(
                'insert_files',
                ['class' => 'save no-display primary', 'label' => __('Insert File'), 'type' => 'button'],
                0,
                0,
                'header'
            );
        }
    }

    /**
     * @return bool|string
     */
    public function getFilebrowserSetupObject()
    {
        $setupObject = [
            'newFolderPrompt' => __('New Folder Name:'),
            'deleteFolderConfirmationMessage' => __('This folder will be deleted recursively') . ',<br>' .
            __('it also may contains files which are not displayed inside the modal. ') . '<br>' .
            __('Please make sure you will NOT delete any folders which are required for Magento to work properly.') . '<br><br>' .
            __('Are you sure you want to delete this folder ?') . '<br>' .
            '<span class="folder-deletion-warning">'.__('Attention: If you delete wrong folders this could break your Magento installation.').'</span>',
            'deleteFileConfirmationMessage' => __('Are you sure you want to delete this file?'),
            'targetElementId' => $this->getTargetElementId(),
            'contentsUrl' => $this->getContentsUrl(),
            'onInsertUrl' => $this->getOnInsertUrl(),
            'newFolderUrl' => $this->getNewfolderUrl(),
            'deleteFolderUrl' => $this->getDeletefolderUrl(),
            'deleteFilesUrl' => $this->getDeleteFilesUrl(),
            'headerText' => $this->getHeaderText(),
            'showBreadcrumbs' => true,
        ];

        return $this->_jsonEncoder->serialize($setupObject);
    }

    /**
     * @return string
     */
    public function getContentsUrl()
    {
        return $this->getUrl('flagbit_flysystem/*/contents');
    }

    /**
     * @return string
     */
    public function getOnInsertUrl()
    {
        return $this->getUrl('flagbit_flysystem/*/onInsert');
    }

    /**
     * @return string
     */
    public function getNewfolderUrl()
    {
        return $this->getUrl('flagbit_flysystem/*/newFolder');
    }

    /**
     * @return string
     */
    protected function getDeletefolderUrl()
    {
        return $this->getUrl('flagbit_flysystem/*/deleteFolder');
    }

    /**
     * @return string
     */
    public function getDeleteFilesUrl()
    {
        return $this->getUrl('flagbit_flysystem/*/deleteFiles');
    }

    /**
     * @return string
     */
    public function getPreviewUrl()
    {
        return $this->getUrl('flagbit_flysystem/*/preview');
    }

    /**
     * @return string
     */
    public function getWysiwygModalUrl()
    {
        return $this->getUrl('cms/wysiwyg_images/index', [
            'target_element_id' => $this->getTargetElementId()
        ]);
    }

    /**
     * @return mixed
     */
    public function getTargetElementId()
    {
        return $this->getRequest()->getParam('target_element_id');
    }

    /**
     * @return mixed
     */
    public function getModalIdentifier()
    {
        return $this->getRequest()->getParam('identifier');
    }
}