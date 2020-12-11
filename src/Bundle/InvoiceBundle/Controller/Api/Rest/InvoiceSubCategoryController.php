<?php

declare(strict_types=1);

namespace Custom\Bundle\InvoiceBundle\Controller\Api\Rest;

use Custom\Bundle\InvoiceBundle\Entity\InvoiceSubCategory;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Oro\Bundle\SoapBundle\Controller\Api\Rest\RestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @RouteResource("subcategory")
 * @Rest\NamePrefix("oro_invoice_subcategories_api_")
 */
class InvoiceSubCategoryController extends RestController implements ClassResourceInterface
{
    /**
     * @ApiDoc(
     *     description="Gets Invoice Sub Category List",
     *     resource=true
     * )
     */
    public function cgetAction()
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
WHERE
    `c`.`invoiceFormEnabled` = 1
ORDER BY
    `c`.`name` DESC
SQL;
        $entityManager = $this->get('doctrine.orm.entity_manager');

        $invoiceCategories = $entityManager->getConnection()->fetchAll($query);

        return $response = new JsonResponse($invoiceCategories);
    }

    /**
     * REST DELETE
     *
     * @Rest\Delete(requirements={"id"="\d+"})
     *
     * @ApiDoc(
     *     description="Delete Invoice Sub Category",
     *     resource=true
     * )
     * @AclAncestor("invoice.subcategory_delete")
     * @return Response
     */
    public function deleteAction(int $id)
    {
        return $this->handleDeleteRequest($id);
    }

    /**
     * {@inheritdoc}
     */
    public function getManager()
    {
        return $this->get('invoice.subcategory_manager.api');
    }

    /**
     * {@inheritdoc}
     */
    public function getForm()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getFormHandler()
    {
        return $this->get('invoice_category.form.handler.entity.api');
    }

    /**
     * {@inheritdoc}
     */
    protected function transformEntityField($field, &$value)
    {
        switch ($field) {
            case 'category':
                if ($value) {
                    $value = $value->getId();
                }
                break;
            default:
                parent::transformEntityField($field, $value);
        }
    }

    /**
     * {@inheritDoc}
     */
    protected function fixFormData(array &$data, $entity)
    {
        /** @var InvoiceSubCategory $entity */
        parent::fixFormData($data, $entity);

        unset($data['id']);

        return true;
    }
}
