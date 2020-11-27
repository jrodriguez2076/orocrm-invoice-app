<?php

declare(strict_types=1);

namespace Custom\Bundle\InvoiceBundle\Entity\Repository;

use Doctrine\ORM\EntityManagerInterface;

class InvoiceCategory
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getInvoiceCategories(): array
    {
        $query = <<<SQL
SELECT 
	`sc`.`id` AS `id`,
	`sc`.`name` AS `name`,
	`c`.`name` AS `category`
FROM 
	`invoice_subcategories` `sc`
LEFT JOIN 
	`invoice_categories` `c` ON `c`.`id` = `sc`.`category_id`
ORDER BY
    `c`.`name` DESC
SQL;
        return $this->entityManager->getConnection()->fetchAll($query);
    }
}
