<?php
namespace Flagbit\Flysystem\Block\Adminhtml\Filesystem;

use \Flagbit\Flysystem\Model\Filesystem\Manager;
use \Magento\Backend\Block\Template\Context;
use \Flagbit\Flysystem\Helper\Filesystem;
use \Magento\Framework\Serialize\Serializer\Json;

/**
 * Class Tree
 * @package Flagbit\Flysystem\Block\Adminhtml\Filesystem
 */
class Tree extends \Magento\Backend\Block\Template
{
    /**
     * @var Manager
     */
    protected $_flysystemManager;

    /**
     * @var Filesystem
     */
    protected $_flysystemHelper;

    /**
     * @var Json
     */
    private $_serializer;

    /**
     * Tree constructor.
     * @param Context $context
     * @param Manager $flysystemManager
     * @param Filesystem $flysystemHelper
     * @param Json $serializer
     * @param array $data
     */
    public function __construct(
        Context $context,
        Manager $flysystemManager,
        Filesystem $flysystemHelper,
        Json $serializer,
        array $data = []
    ) {
        $this->_flysystemManager = $flysystemManager;
        $this->_flysystemHelper = $flysystemHelper;
        $this->_serializer = $serializer;
        parent::__construct($context, $data);
    }

    /**
     * @return bool|string
     * @throws \Exception
     */
    public function getTreeJson()
    {
        try {
            $path = $this->_flysystemHelper->getCurrentPath();

            $contents = $this->_flysystemManager->getAdapter()->listContents($path);

            $jsonArray = [];
            foreach ($contents as $contentKey => $content) {
                if ($content['type'] === 'dir' && $content['basename'][0] !== '.') {
                    $jsonArray [] = [
                        'text' => $this->_flysystemHelper->getShortFilename($content['path']),
                        'id' => $this->_flysystemHelper->idEncode('/' . $content['path']),
                        'path' => '/' . $content['path'],
                        'cls' => 'folder'
                    ];
                }
            }
        } catch (\Exception $e) {
            $jsonArray = [];
        }

        return $this->_serializer->serialize($jsonArray);
    }

    /**
     * Json source URL
     *
     * @return string
     */
    public function getTreeLoaderUrl()
    {
        return $this->getUrl('flagbit_flysystem/*/treeJson');
    }

    /**
     * Root node name of tree
     *
     * @return \Magento\Framework\Phrase
     */
    public function getRootNodeName()
    {
        return __('Storage Root');
    }

    /**
     * Return tree node full path based on current path
     *
     * @return array
     */
    public function getTreeCurrentPath()
    {
        $treePath = ['root'];
        if ($path = $this->_flysystemManager->getSession()->getCurrentPath()) {
            //$path = str_replace('/', '', $path);
            $relative = [];
            foreach (explode('/', $path) as $dirName) {
                if ($dirName) {
                    $relative[] = $dirName;
                    $treePath[] = $this->_flysystemHelper->idEncode(implode('/', $relative));
                }
            }
        }
        return $treePath;
    }

    /**
     * @return bool|string
     */
    public function getTreeWidgetOptions()
    {
        return $this->_serializer->serialize([
            "folderTree" => [
                "rootName" => $this->getRootNodeName(),
                "url" => $this->getTreeLoaderUrl(),
                "currentPath" => array_reverse($this->getTreeCurrentPath())
            ]
        ]);
    }
}
