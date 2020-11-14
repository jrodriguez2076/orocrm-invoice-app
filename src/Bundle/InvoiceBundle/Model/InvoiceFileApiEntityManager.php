<?php

namespace Custom\Bundle\InvoiceBundle\Model;

use Custom\Bundle\InvoiceBundle\Entity\InvoiceFile;
use Doctrine\Common\Persistence\ObjectManager;
use Oro\Bundle\SoapBundle\Entity\Manager\ApiEntityManager;

class InvoiceFileApiEntityManager extends ApiEntityManager
{
    /**
     * Constructor
     *
     * @param string $class Entity name
     * @param ObjectManager $om Object manager
     */
    public function __construct($class, ObjectManager $om)
    {
        parent::__construct($class, $om);
    }

    /**
     * {@inheritdoc}
     */
    public function createEntity()
    {
        $invoiceFile = new InvoiceFile();
        $invoiceFile->setUploadedAt(new \DateTime());

        return $invoiceFile;
    }
}
