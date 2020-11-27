<?php

declare(strict_types=1);

namespace Custom\Bundle\InvoiceBundle\Controller;

use Custom\Bundle\InvoiceBundle\Entity\InvoiceCategory;
use Custom\Bundle\InvoiceBundle\Form\Type\InvoiceCategoryType;
use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/category")
 */
class InvoiceCategoryController extends AbstractController
{
    /**
     * @Route("/", name="invoice_category_index")
     * @Template
     * @Acl(
     *     id="invoice.category_view",
     *     type="entity",
     *     class="CustomInvoiceBundle:InvoiceCategory",
     *     permission="VIEW"
     * )
     */
    public function indexAction()
    {
        return ['gridName' => 'invoice-categories-grid'];
    }

    /**
     * @Route("/view/{id}", name="invoice_category_view", requirements={"id"="\d+"})
     * @Template("CustomInvoiceBundle:InvoiceCategory:view.html.twig")
     * @Acl(
     *     id="invoice.category_view",
     *     type="entity",
     *     class="CustomInvoiceBundle:InvoiceCategory",
     *     permission="VIEW"
     * )
     */
    public function viewAction(InvoiceCategory $category)
    {
        return [
            'entity' => $category
        ];
    }

    /**
     * @Route("/create", name="invoice_category_create")
     * @Template("CustomInvoiceBundle:InvoiceCategory:update.html.twig")
     * @Acl(
     *     id="invoice.category_create",
     *     type="entity",
     *     class="CustomInvoiceBundle:InvoiceCategory",
     *     permission="CREATE"
     * )
     */
    public function createAction(Request $request)
    {
        $category = new InvoiceCategory();

        return $this->update($category, $request);
    }

    /**
     * @Route("/update/{id}", name="invoice_category_update", requirements={"id":"\d+"}, defaults={"id":0})
     * @Template()
     * @Acl(
     *     id="invoice.category_update",
     *     type="entity",
     *     class="CustomInvoiceBundle:InvoiceCategory",
     *     permission="EDIT"
     * )
     */
    public function updateAction(InvoiceCategory $category, Request $request)
    {
        return $this->update($category, $request);
    }

    private function update(InvoiceCategory $category, Request $request)
    {
        $form = $this->get('form.factory')->create(InvoiceCategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('invoice_category_view', ['id' => $category->getId()]);
        }

        return [
            'entity' => $category,
            'form' => $form->createView(),
        ];
    }
}
