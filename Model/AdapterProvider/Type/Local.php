<?php
namespace Flagbit\Flysystem\Model\AdapterProvider\Type;

use \Exception;
use \Flagbit\Flysystem\Adapter\FilesystemAdapter;
use \Flagbit\Flysystem\Adapter\FilesystemAdapterFactory;
use \Flagbit\Flysystem\Adapter\FilesystemManager;
use \Flagbit\Flysystem\Model\AdapterProvider\AbstractType;
use \Magento\Framework\Serialize\Serializer\Json;
use \Psr\Log\LoggerInterface;

class Local extends AbstractType
{
    const CONFIG_ROOT_PATH = 'root_path';
    const CONFIG_LOCK_DURING_WRITE = 'lock_during_write';

    /**
     * @var array
     */
    protected static $_configFields = [
        self::CONFIG_ROOT_PATH => [
            'type' => 'text',
            'default' => ''
        ],
        self::CONFIG_LOCK_DURING_WRITE => [
            'type' => 'bool',
            'default' => true
        ]
    ];

    /**
     * @var LoggerInterface
     */
    protected $_logger;

    /**
     * @var FilesystemAdapterFactory
     */
    protected $_flysystemFactory;

    /**
     * @var FilesystemManager
     */
    protected $_flysystemManager;

    /**
     * Local constructor.
     * @param Json $json
     * @param LoggerInterface $logger
     * @param FilesystemAdapterFactory $flysystemFactory
     * @param FilesystemManager $flysystemManager
     * @param null $config
     */
    public function __construct(
        Json $json,
        LoggerInterface $logger,
        FilesystemAdapterFactory $flysystemFactory,
        FilesystemManager $flysystemManager,
        $config = null
    ) {
        $this->_logger = $logger;
        $this->_flysystemFactory = $flysystemFactory;
        $this->_flysystemManager = $flysystemManager;
        parent::__construct($json, $config);
    }

    /**
     * @return FilesystemAdapter|null
     */
    public function createAdapter(): ?FilesystemAdapter
    {
        try {
            if (empty($path)) {
                $path = $this->_config[self::CONFIG_ROOT_PATH];
                if (empty($path)) {
                    $path = '/';
                }
            }

            return $this->_flysystemFactory->create($this->_flysystemManager->createLocalDriver($path));
        } catch (Exception $e) {
            $this->_logger->critical($e->getMessage());
            return null;
        }
    }
}
