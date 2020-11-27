<?php

declare(strict_types=1);

namespace Custom\Bundle\InvoiceBundle\Migrations\Schema\v3;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\EntityExtendBundle\EntityConfig\ExtendScope;
use Oro\Bundle\EntityExtendBundle\Migration\Extension\ExtendExtension;
use Oro\Bundle\EntityExtendBundle\Migration\Extension\ExtendExtensionAwareInterface;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\OrderedMigrationInterface;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class ExtendInvoiceFile implements Migration, ExtendExtensionAwareInterface, OrderedMigrationInterface
{
    /**
     * @var ExtendExtension
     */
    protected $extendExtension;

    public function getOrder()
    {
        return 50;
    }

    public function setExtendExtension(ExtendExtension $extendExtension): void
    {
        $this->extendExtension = $extendExtension;
    }

    public function up(Schema $schema, QueryBag $queries): void
    {
        $table = $schema->getTable('invoice_files');

        $this->extendExtension->addManyToOneRelation(
            $schema,
            $table,
            'category',
            $schema->getTable('invoice_subcategories'),
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
    }
}
