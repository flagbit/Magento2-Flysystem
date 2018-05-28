<?php
namespace Flagbit\Flysystem\Block\Adminhtml\Filesystem;

use \Magento\Backend\Block\Template\Context;
use \Flagbit\Flysystem\Helper\Filesystem;
use \Magento\Framework\Registry;
use \Magento\Framework\Serialize\Serializer\Json;

/**
 * Directory tree renderer for Flagbit Flysystem
 */
class Tree extends \Magento\Backend\Block\Template
{
    /**
     * @var Registry
     */
    protected $_coreRegistry;

    /**
     * @var Filesystem
     */
    protected $_filesystemHelper;

    /**
     * @var Json
     */
    private $serializer;

    /**
     * Tree constructor.
     * @param Context $context
     * @param Filesystem $filesystemHelper
     * @param Registry $registry
     * @param Json $serializer
     * @param array $data
     */
    public function __construct(
        Context $context,
        Filesystem $filesystemHelper,
        Registry $registry,
        Json $serializer,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        $this->_filesystemHelper = $filesystemHelper;
        $this->serializer = $serializer;
        parent::__construct($context, $data);
    }

    /**
     * Json tree builder
     *
     * @return string
     */
    public function getTreeJson()
    {
        $manager = $this->_coreRegistry->registry('flysystem_manager');

        $path = $this->_filesystemHelper->getCurrentPath();

        $contents = $manager->getAdapter()->listContents($path);

        $jsonArray = [];
        foreach($contents as $contentKey => $content) {
            if($content['type'] === 'dir' && $content['basename'][0] !== '.') {
                $jsonArray [] = [
                    'text' => $this->_filesystemHelper->getShortFilename($content['path']),
                    'id' => $this->_filesystemHelper->idEncode('/'.$content['path']),
                    'path' => '/'.$content['path'],
                    'cls' => 'folder'
                ];
            }
        }

        return $this->serializer->serialize($jsonArray);
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
        if ($path = $this->_coreRegistry->registry('flysystem_manager')->getSession()->getCurrentPath()) {
            //$path = str_replace('/', '', $path);
            $relative = [];
            foreach (explode('/', $path) as $dirName) {
                if ($dirName) {
                    $relative[] = $dirName;
                    $treePath[] = $this->_filesystemHelper->idEncode(implode('/', $relative));
                }
            }
        }
        return $treePath;
    }

    /**
     * Get tree widget options
     *
     * @return array
     */
    public function getTreeWidgetOptions()
    {
        return $this->serializer->serialize([
            "folderTree" => [
                "rootName" => $this->getRootNodeName(),
                "url" => $this->getTreeLoaderUrl(),
                "currentPath" => array_reverse($this->getTreeCurrentPath())
            ]
        ]);
    }
}
