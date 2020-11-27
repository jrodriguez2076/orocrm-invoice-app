<?php

declare(strict_types=1);

namespace Custom\Bundle\InvoiceBundle\Controller\Api\Rest;

use Custom\Bundle\InvoiceBundle\Entity\InvoiceSubCategory;
use DateTime;
use Custom\Bundle\InvoiceBundle\Entity\InvoiceFile;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Oro\Bundle\AttachmentBundle\Entity\File;
use Oro\Bundle\ContactBundle\Entity\Contact;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Oro\Bundle\SoapBundle\Controller\Api\Rest\RestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @RouteResource("file")
 * @Rest\NamePrefix("oro_invoice_files_api_")
 */
class InvoiceFileController extends RestController implements ClassResourceInterface
{
    /**
     * @ApiDoc(
     *     description="Creates Invoice File Association",
     *     resource=true
     * )
     */
    public function postAction(Request $request)
    {
        $entityManager = $this->get('doctrine.orm.entity_manager');
        $relatedContactId = $request->get('file')['relatedContact'];
        $relatedContact = $entityManager->getRepository(Contact::class)->findOneBy(['id' => $relatedContactId]);
        $categoryId = $request->get('file')['category'];
        $category = $entityManager->getRepository(InvoiceSubCategory::class)->findOneBy(['id' => $categoryId]);
        $invoiceFileEntity = new InvoiceFile();
        $invoiceFileEntity->setRelatedContact($relatedContact);
        $invoiceFileEntity->setCategory($category);
        $invoiceFileEntity->setUploadedAt(new DateTime());

        $uploadedFile = $request->files->all()['file']['file']['content'];

        $newFile = new File();
        $newFile->setFile($uploadedFile);
        $newFile->setOriginalFilename($uploadedFile->getClientOriginalName());
        $newFile->setMimeType($uploadedFile->getClientMimeType());
        $newFile->setFileSize($uploadedFile->getClientSize());
        $entityManager->persist($newFile);

        $invoiceFileEntity->setFile($newFile);

        $this->get('invoice.auto_assignment.listener')->assignAccountByContact($invoiceFileEntity);

        $entityManager->persist($invoiceFileEntity);
        $entityManager->flush();

        return new JsonResponse([$invoiceFileEntity->getId()], 201);
    }

    /**
     * REST DELETE
     *
     * @Rest\Delete(requirements={"id"="\d+"})
     *
     * @ApiDoc(
     *     description="Delete Invoice File",
     *     resource=true
     * )
     * @AclAncestor("invoice.file_delete")
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
        return $this->get('invoice.file_manager.api');
    }

    /**
     * {@inheritdoc}
     */
    public function getForm()
    {
        return $this->get('invoice.form.type.file.api');
    }

    /**
     * {@inheritdoc}
     */
    public function getFormHandler()
    {
        return $this->get('invoice_file.form.handler.entity.api');
    }

    /**
     * {@inheritdoc}
     */
    protected function transformEntityField($field, &$value)
    {
        switch ($field) {
            case 'relatedAccount':
            case 'relatedContact':
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
        /** @var InvoiceFile $entity */
        parent::fixFormData($data, $entity);

        unset($data['id']);
        unset($data['uploadedAt']);

        return true;
    }
}
