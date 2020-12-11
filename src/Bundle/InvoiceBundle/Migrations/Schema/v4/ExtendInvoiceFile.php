<?php

declare(strict_types=1);

namespace Custom\Bundle\InvoiceBundle\Migrations\Schema\v4;

use Custom\Bundle\InvoiceBundle\Entity\InvoiceFile;
use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\EntityConfigBundle\Migration\RemoveFieldQuery;
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

        if ($table->hasForeignKey('FK_3BEF10EC4DA1DB9C')) {
            $table->removeForeignKey('FK_3BEF10EC4DA1DB9C');
        }

        $queries->addPostQuery(
            new RemoveFieldQuery(
                InvoiceFile::class,
                'related_contact'
            )
        );

        $queries->addPostQuery(
            new RemoveFieldQuery(
                InvoiceFile::class,
                'relatedContact'
            )
        );

        if ($table->hasColumn('relatedContact_id')) {
            $table->dropColumn('relatedContact_id');
        }
    }
}
