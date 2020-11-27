<?php

declare(strict_types=1);

namespace Custom\Bundle\InvoiceBundle\Controller;

use Custom\Bundle\InvoiceBundle\Entity\InvoiceFile;
use Custom\Bundle\InvoiceBundle\Form\Type\InvoiceFileType;
use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/file")
 */
class InvoiceFileController extends AbstractController
{
    /**
     * @Route("/", name="invoice_file_index")
     * @Template
     * @Acl(
     *     id="invoice.file_view",
     *     type="entity",
     *     class="CustomInvoiceBundle:InvoiceFile",
     *     permission="VIEW"
     * )
     */
    public function indexAction()
    {
        return ['gridName' => 'invoice-files-grid'];
    }

    /**
     * @Route("/view/{id}", name="invoice_file_view", requirements={"id"="\d+"})
     * @Template("CustomInvoiceBundle:InvoiceFile:view.html.twig")
     * @Acl(
     *     id="invoice.file_view",
     *     type="entity",
     *     class="CustomInvoiceBundle:InvoiceFile",
     *     permission="VIEW"
     * )
     */
    public function viewAction(InvoiceFile $invoiceFile)
    {
        return [
            'entity' => $invoiceFile
        ];
    }

    /**
     * @Route("/create", name="invoice_file_create")
     * @Template("CustomInvoiceBundle:InvoiceFile:update.html.twig")
     * @Acl(
     *     id="invoice.file_create",
     *     type="entity",
     *     class="CustomInvoiceBundle:InvoiceFile",
     *     permission="CREATE"
     * )
     */
    public function createAction(Request $request)
    {
        $invoiceFile = new InvoiceFile();
        $invoiceFile->setUploadedAt(new \DateTime());

        return $this->update($invoiceFile, $request);
    }

    /**
     * @Route("/update/{id}", name="invoice_file_update", requirements={"id":"\d+"}, defaults={"id":0})
     * @Template()
     * @Acl(
     *     id="invoice.file_update",
     *     type="entity",
     *     class="CustomInvoiceBundle:InvoiceFile",
     *     permission="EDIT"
     * )
     */
    public function updateAction(InvoiceFile $invoiceFile, Request $request)
    {
        return $this->update($invoiceFile, $request);
    }

    private function update(InvoiceFile $invoiceFile, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $categoryList = $this->get('invoice.provider.categories')->getInvoiceCategories();
        foreach ($categoryList as $row) {
            $categories[$row['category']][$row['name']] = $row['id'];
        }
        $form = $this->get('form.factory')->create(InvoiceFileType::class, $invoiceFile, ['categories' => $categories, 'entityManager' => $entityManager, 'api' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($invoiceFile);
            $entityManager->flush();

            return $this->redirectToRoute('invoice_file_view', ['id' => $invoiceFile->getId()]);
        }

        return [
            'entity' => $invoiceFile,
            'form' => $form->createView(),
        ];
    }
}
