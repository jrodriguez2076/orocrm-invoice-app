<?php

declare(strict_types=1);

namespace Custom\Bundle\AccountBundle\Controller\Api\Rest;

use FOS\RestBundle\Controller\Annotations\NamePrefix;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Oro\Bundle\SoapBundle\Controller\Api\Rest\RestController;
use Oro\Bundle\SoapBundle\Entity\Manager\ApiEntityManager;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @RouteResource("invoiceaccount")
 * @NamePrefix("custom_oro_api_")
 */
class InvoiceFormController extends RestController
{
    public function cgetAction(): JsonResponse
    {
        $query = <<<SQL
SELECT 
	`account`.`id` AS `id`,
	`account`.`name` AS `name`
FROM 
	`orocrm_account` `account`
ORDER BY
    `account`.`name` DESC
SQL;
        $entityManager = $this->get('doctrine.orm.entity_manager');

        $accounts = $entityManager->getConnection()->fetchAll($query);
        $formattedAccounts = [];

        foreach ($accounts as $account) {
            $formattedAccounts[$account['name']] = $account['id'];
        }

        return $response = new JsonResponse($formattedAccounts);
    }

    public function getForm(): void
    {
    }

    public function getFormHandler(): void
    {
    }

    /**
     * Get entity Manager.
     *
     * @return ApiEntityManager
     */
    public function getManager()
    {
        return $this->get('oro_account.account.manager');
    }
}
