<?php

declare(strict_types=1);

namespace Custom\Bundle\InvoiceBundle\Migrations\Schema\v1;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\AttachmentBundle\Migration\Extension\AttachmentExtension;
use Oro\Bundle\AttachmentBundle\Migration\Extension\AttachmentExtensionAwareInterface;
use Oro\Bundle\EntityExtendBundle\EntityConfig\ExtendScope;
use Oro\Bundle\EntityExtendBundle\Migration\Extension\ExtendExtension;
use Oro\Bundle\EntityExtendBundle\Migration\Extension\ExtendExtensionAwareInterface;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\OrderedMigrationInterface;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class CreateInvoiceTables implements Migration, ExtendExtensionAwareInterface, AttachmentExtensionAwareInterface, OrderedMigrationInterface
{
    /**
     * @var ExtendExtension
     */
    protected $extendExtension;

    /**
     * @var AttachmentExtension
     */
    protected $attachmentExtension;

    public function getOrder()
    {
        return 50;
    }

    /**
     * {@inheritdoc}
     */
    public function setAttachmentExtension(AttachmentExtension $attachmentExtension): void
    {
        $this->attachmentExtension = $attachmentExtension;
    }

    public function setExtendExtension(ExtendExtension $extendExtension): void
    {
        $this->extendExtension = $extendExtension;
    }

    public function up(Schema $schema, QueryBag $queries): void
    {
        $this->createInvoiceFilesTable($schema);
    }

    protected function createInvoiceFilesTable(Schema $schema): void
    {
        $table = $schema->createTable('invoice_files');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('uploadedAt', 'datetime', []);
        $table->setPrimaryKey(['id']);

        $this->extendExtension->addManyToOneRelation(
            $schema,
            $table,
            'relatedAccount',
            $schema->getTable('orocrm_account'),
            'id',
            [
                'extend' => [
                    'owner' => ExtendScope::OWNER_CUSTOM,
                    'is_extend' => true,
                    'without_default' => true,
                ],
                'datagrid' => [
                    'is_visible' => true,
                ],
                'view' => [
                    'is_displayable' => true,
                ],
            ]
        );

        $this->extendExtension->addManyToOneRelation(
            $schema,
            $table,
            'relatedContact',
            $schema->getTable('orocrm_contact'),
            'id',
            [
                'extend' => [
                    'owner' => ExtendScope::OWNER_CUSTOM,
                    'is_extend' => true,
                    'without_default' => true,
                ],
                'datagrid' => [
                    'is_visible' => true,
                ],
                'view' => [
                    'is_displayable' => true,
                ],
            ]
        );

        $this->attachmentExtension->addFileRelation(
            $schema,
            'invoice_files',
            'file',
            [
                'extend' => [
                    'owner' => ExtendScope::OWNER_CUSTOM,
                    'is_extend' => true,
                    'without_default' => true,
                ],
            ],
            100
        );
    }
}
