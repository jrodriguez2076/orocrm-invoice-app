<?php

declare(strict_types=1);

namespace Custom\Bundle\ContactBundle\Migrations\Schema\v1;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\EntityExtendBundle\EntityConfig\ExtendScope;
use Oro\Bundle\EntityExtendBundle\Migration\Extension\ExtendExtension;
use Oro\Bundle\EntityExtendBundle\Migration\Extension\ExtendExtensionAwareInterface;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\OrderedMigrationInterface;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class ExtendContact implements Migration, OrderedMigrationInterface
{
    public function getOrder()
    {
        return 50;
    }

    public function up(Schema $schema, QueryBag $queries): void
    {
        $table = $schema->getTable('orocrm_contact');

        $table->addColumn(
            'idDocument',
            'string',
            [
                'oro_options' => [
                    'extend' => [
                        'is_extend' => true,
                        'owner' => ExtendScope::OWNER_CUSTOM,
                    ],
                    'datagrid' => [
                        'is_visible' => true,
                    ],
                    'view' => [
                        'is_displayable' => false,
                    ],
                    'search' => [
                        'searchable' => true,
                    ],
                ],
            ]
        );
    }
}
