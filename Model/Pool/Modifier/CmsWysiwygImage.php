<?php
namespace Flagbit\Flysystem\Model\Pool\Modifier;

use \Flagbit\Flysystem\Helper\Errors;
use \Flagbit\Flysystem\Helper\Filesystem;
use \Flagbit\Flysystem\Model\Filesystem\Manager;
use \Flagbit\Flysystem\Model\Filesystem\TmpManager;
use \Flagbit\Flysystem\Model\Pool\ModifierInterface;
use \Magento\Framework\Exception\LocalizedException;
use \Psr\Log\LoggerInterface;

/**
 * Class CmsWysiwygImage
 * @package Flagbit\Flysystem\Model\Pool\Modifier
 */
class CmsWysiwygImage implements ModifierInterface
{
    /**
     * @var Manager
     */
    protected $manager;

    /**
     * @var TmpManager
     */
    protected $tmpManager;

    /**
     * @var Filesystem
     */
    protected $flysystemHelper;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var string|null
     */
    protected $filename = null;

    /**
     * @var bool
     */
    protected $as_is = false;

    /**
     * CmsWysiwygImage constructor.
     * @param Manager $manager
     * @param TmpManager $tmpManager
     * @param Filesystem $flysystemHelper
     * @param LoggerInterface $logger
     */
    public function __construct(
        Manager $manager,
        TmpManager $tmpManager,
        Filesystem $flysystemHelper,
        LoggerInterface $logger
    ) {
        $this->manager = $manager;
        $this->tmpManager = $tmpManager;
        $this->flysystemHelper = $flysystemHelper;
        $this->logger = $logger;
    }

    /**
     * @param array $data
     * @return string|null
     * @throws LocalizedException
     * @throws \League\Flysystem\FileExistsException
     * @throws \League\Flysystem\FileNotFoundException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function modifyFile(array $data): ?string
    {
        $this->filename = null;
        if (!isset($data['filename']) || empty($data['filename'])) {
            throw new LocalizedException(Errors::getErrorMessage(631));
        }
        $this->filename = $data['filename'];
        $this->as_is = (isset($data['as_is']) && $data['as_is']) ? $data['as_is'] : false;

        $content = $this->manager->getAdapter()->read($this->filename);

        $fullFilePath = trim($this->manager->getPath(), '/') . '/' . trim($this->filename, '/');
        $mediaPath = trim($this->tmpManager->getDirectoryListMedia()->getAbsolutePath(), '/');

        if (strpos($fullFilePath, $mediaPath) === false) {
            $this->filename = $this->tmpManager->writeWysiwygFile(basename($this->filename), $content);
        } else {
            $this->filename = trim(str_replace($mediaPath, '', $fullFilePath), '/');
        }

        $image = $this->flysystemHelper->getImageHtmlDeclaration($this->filename, $this->as_is);
        return $image;
    }
}