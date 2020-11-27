<?php

declare(strict_types=1);

namespace Custom\Bundle\ContactBundle\Controller\Api\Rest;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Exception;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\NamePrefix;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Oro\Bundle\AccountBundle\Entity\Account;
use Oro\Bundle\ContactBundle\Entity\ContactEmail;
use Oro\Bundle\EmailBundle\Entity\Mailbox;
use Oro\Bundle\SoapBundle\Controller\Api\Rest\RestController;
use Oro\Bundle\SoapBundle\Entity\Manager\ApiEntityManager;
use Oro\Bundle\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @RouteResource("emailcontact")
 * @NamePrefix("custom_oro_api_")
 */
class ContactEmailController extends RestController
{
    public function getAction(Request $request): JsonResponse
    {
        $email = $request->query->get('email');
        if (!$email) {
            return $response = new JsonResponse(['error' => 'Email not found.']);
        }

        /** @var EntityManager $entityManager */
        $entityManager = $this->container->get('doctrine.orm.entity_manager');

        $contactEmail = $this->getContactEmail($email, $entityManager);
        if (!$contactEmail) {
            return $response = new JsonResponse(['error' => 'Email not found.']);
        }

        $contactEntity = $contactEntity = $contactEmail->getEmailOwner();
        if (!$contactEntity) {
            return $response = new JsonResponse(['error' => 'Contact not found.']);
        }

        $contact['contact']['id'] = $contactEntity->getId();

        return $response = new JsonResponse($contact);
    }

    public function cgetAction(): JsonResponse
    {
        $query = <<<SQL
SELECT 
	`contact`.`id` AS `id`,
	`contactEmail`.`email` AS `email`,
	CONCAT_WS(' ', `contact`.`first_name`, `contact`.`last_name`) as contactName
FROM 
	`orocrm_contact` `contact`
LEFT JOIN
    `orocrm_contact_email` `contactEmail` ON `contactEmail`.`owner_id` = `contact`.`id`
ORDER BY
    `contact`.`email` DESC
SQL;
        $entityManager = $this->get('doctrine.orm.entity_manager');

        $contacts = $entityManager->getConnection()->fetchAll($query);
        $formattedContacts = [];

        foreach ($contacts as $contact) {
            $formattedContacts[sprintf('%s / %s', $contact['email'], $contact['contactName'])] = $contact['email'];
        }

        return $response = new JsonResponse($formattedContacts);
    }

    protected function getContactEmail(string $email, EntityManager $entityManager): ?ContactEmail
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $entityManager->createQueryBuilder();
        /** @var ContactEmail $contactEmail */
        return $queryBuilder->select('e')
            ->from(ContactEmail::class, 'e')
            ->where('e.email = :email')
            ->setParameter('email', $email)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
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
        return $this->get('oro_contact.contact.manager');
    }
}
