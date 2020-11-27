<?php

declare(strict_types=1);

namespace Custom\Bundle\InvoiceBundle\Controller\Api\Rest;

use Custom\Bundle\InvoiceBundle\Entity\InvoiceCategory;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Oro\Bundle\SoapBundle\Controller\Api\Rest\RestController;
use Symfony\Component\HttpFoundation\Response;

/**
 * @RouteResource("category")
 * @Rest\NamePrefix("oro_invoice_categories_api_")
 */
class InvoiceCategoryController extends RestController implements ClassResourceInterface
{
    /**
     * REST DELETE
     *
     * @Rest\Delete(requirements={"id"="\d+"})
     *
     * @ApiDoc(
     *     description="Delete Invoice Category",
     *     resource=true
     * )
     * @AclAncestor("invoice.category_delete")
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
        return $this->get('invoice.category_manager.api');
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
    }

    /**
     * {@inheritDoc}
     */
    protected function fixFormData(array &$data, $entity)
    {
        /** @var InvoiceCategory $entity */
        parent::fixFormData($data, $entity);

        unset($data['id']);

        return true;
    }
}
