<?php
namespace Flagbit\Flysystem\Model\Pool\Modifier;

use \Flagbit\Flysystem\Helper\Errors;
use \Flagbit\Flysystem\Model\Filesystem\Manager;
use \Flagbit\Flysystem\Model\Filesystem\TmpManager;
use \Flagbit\Flysystem\Model\Pool\ModifierInterface;
use \Magento\Framework\Exception\LocalizedException;
use \Psr\Log\LoggerInterface;

/**
 * Class CategoryImage
 * @package Flagbit\Flysystem\Model\Pool\Modifier
 */
class CategoryImage implements ModifierInterface
{
    /**
     * @var TmpManager
     */
    private $_tmpManager;

    /**
     * @var Manager
     */
    private $_manager;

    /**
     * @var LoggerInterface
     */
    private $_logger;

    /**
     * @var string|null
     */
    protected $filename = null;

    /**
     * CategoryImage constructor.
     * @param TmpManager $tmpManager
     * @param Manager $manager
     * @param LoggerInterface $logger
     */
    public function __construct(
        TmpManager $tmpManager,
        Manager $manager,
        LoggerInterface $logger
    ) {
        $this->_tmpManager = $tmpManager;
        $this->_manager = $manager;
        $this->_logger = $logger;
    }

    /**
     * @param array $data
     * @return string|null
     * @throws LocalizedException
     * @throws \League\Flysystem\FileNotFoundException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function modifyFile(array $data)
    {
        $this->filename = null;
        if(!isset($data['filename']) || empty($data['filename'])) {
            throw new LocalizedException(Errors::getErrorMessage(631));
        }

        $this->filename = $data['filename'];

        $file = $this->createFileArray();

        $result = $this->_tmpManager->createCategoryTmp($file);
        return json_encode($result);
    }

    /**
     * @return array
     * @throws LocalizedException
     * @throws \League\Flysystem\FileNotFoundException
     */
    protected function createFileArray(): array {
        $file = [
            'name' => basename($this->filename),
            'type' => $this->_manager->getAdapter()->getMimetype($this->filename),
            'tmp_name' => $this->_tmpManager->getAbsoluteTmpPath($this->filename),
            'error' => 0,
            'size' => $this->_manager->getAdapter()->getSize($this->filename)
        ];

        return $file;
    }
}
