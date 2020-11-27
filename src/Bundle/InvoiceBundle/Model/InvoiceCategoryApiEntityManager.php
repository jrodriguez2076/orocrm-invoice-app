<?php

namespace Custom\Bundle\InvoiceBundle\Model;

use Doctrine\Common\Persistence\ObjectManager;
use Oro\Bundle\SoapBundle\Entity\Manager\ApiEntityManager;

class InvoiceCategoryApiEntityManager extends ApiEntityManager
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
}
