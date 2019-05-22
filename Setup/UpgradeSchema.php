<?php
namespace Flagbit\Flysystem\Setup;

use \Magento\Framework\DB\Ddl\Table;
use \Magento\Framework\Setup\ModuleContextInterface;
use \Magento\Framework\Setup\SchemaSetupInterface;
use \Magento\Framework\Setup\UpgradeSchemaInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * @var SchemaSetupInterface
     */
    protected $setup;

    /**
     * @var ModuleContextInterface
     */
    protected $context;

    /**
     * @var array
     */
    protected $versionChanges = [
        '3.0.0'
    ];

    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $this->setup = $setup;
        $this->context = $context;

        $this->setup->startSetup();

        foreach ($this->versionChanges as $version) {
            if(version_compare($this->context->getVersion(), $version, '<')) {
                $strVersion = str_replace('.', '_', $version);

                $this->{'upgrade_'.$strVersion}();
            }
        }

        $this->setup->endSetup();
    }

    /**
     * @throws \Zend_Db_Exception
     */
    public function upgrade_3_0_0()
    {
        $this->createAdapterListTable();
        $this->createAdapterFileTable();
    }

    /**
     * @throws \Zend_Db_Exception
     */
    public function createAdapterListTable()
    {
        $table = $this->setup->getConnection()
            ->newTable($this->setup->getTable('flagbit_flysystem_adapter'))
            ->addColumn(
                'adapter_id',
                Table::TYPE_INTEGER,
                null,
                ['unsinged' => true, 'nullable' => false, 'default' => '0'],
                'Adapter ID'
            )
            ->addColumn(
                'name',
                Table::TYPE_TEXT,
                255,
                [],
                'Name'
            )
            ->addColumn(
                'type',
                Table::TYPE_TEXT,
                255,
                [],
                'Adapter Type'
            )
            ->addColumn(
                'config_json',
                Table::TYPE_TEXT,
                '64k',
                [],
                'Config'
            )
            ->addColumn(
                'created_time',
                Table::TYPE_DATETIME,
                null,
                [],
                'Created At'
            )
            ->addColumn(
                'updated_time',
                Table::TYPE_DATETIME,
                null,
                [],
                'Updated At'
            )
            ->addIndex(
                $this->setup->getIdxName('flagbit_flysystem_adapter_adapter_id', ['adapter_id']),
                ['adapter_id']
            );

        $this->setup->getConnection()->createTable($table);
    }

    /**
     * @throws \Zend_Db_Exception
     */
    public function createAdapterFileTable()
    {
        $table = $this->setup->getConnection()
            ->newTable($this->setup->getTable('flagbit_flysystem_files'))
            ->addColumn(
                'file_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'File ID'
            )
            ->addColumn(
                'adapter_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true],
                'Adapter ID'
            )
            ->addColumn(
                'remote_path',
                Table::TYPE_TEXT,
                255,
                [],
                'Remote absolute path'
            )
            ->addColumn(
                'remote_rel_path',
                Table::TYPE_TEXT,
                255,
                [],
                'Remote relative path'
            )
            ->addColumn(
                'local_path',
                Table::TYPE_TEXT,
                255,
                [],
                'Local absolute path'
            )
            ->addColumn(
                'local_rel_path',
                Table::TYPE_TEXT,
                255,
                [],
                'Local relative path'
            )
            ->addColumn(
                'filename',
                Table::TYPE_TEXT,
                255,
                [],
                'Filename with type'
            )
            ->addColumn(
                'mimetype',
                Table::TYPE_TEXT,
                20,
                [],
                'Mimetype of file'
            );

        $this->setup->getConnection()->createTable($table);
    }
}