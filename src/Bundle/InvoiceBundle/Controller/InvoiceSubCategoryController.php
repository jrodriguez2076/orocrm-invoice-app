<?php

declare(strict_types=1);

namespace Custom\Bundle\InvoiceBundle\Controller;

use Custom\Bundle\InvoiceBundle\Entity\InvoiceSubCategory;
use Custom\Bundle\InvoiceBundle\Form\Type\InvoiceSubCategoryType;
use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/subcategory")
 */
class InvoiceSubCategoryController extends AbstractController
{
    /**
     * @Route("/", name="invoice_subcategory_index")
     * @Template
     * @Acl(
     *     id="invoice.subcategory_view",
     *     type="entity",
     *     class="CustomInvoiceBundle:InvoiceSubCategory",
     *     permission="VIEW"
     * )
     */
    public function indexAction()
    {
        return ['gridName' => 'invoice-subcategories-grid'];
    }

    /**
     * @Route("/view/{id}", name="invoice_subcategory_view", requirements={"id"="\d+"})
     * @Template("CustomInvoiceBundle:InvoiceSubCategory:view.html.twig")
     * @Acl(
     *     id="invoice.subcategory_view",
     *     type="entity",
     *     class="CustomInvoiceBundle:InvoiceSubCategory",
     *     permission="VIEW"
     * )
     */
    public function viewAction(InvoiceSubCategory $subCategory)
    {
        return [
            'entity' => $subCategory
        ];
    }

    /**
     * @Route("/create", name="invoice_subcategory_create")
     * @Template("CustomInvoiceBundle:InvoiceSubCategory:update.html.twig")
     * @Acl(
     *     id="invoice.subcategory_create",
     *     type="entity",
     *     class="CustomInvoiceBundle:InvoiceSubCategory",
     *     permission="CREATE"
     * )
     */
    public function createAction(Request $request)
    {
        $subCategory = new InvoiceSubCategory();

        return $this->update($subCategory, $request);
    }

    /**
     * @Route("/update/{id}", name="invoice_subcategory_update", requirements={"id":"\d+"}, defaults={"id":0})
     * @Template()
     * @Acl(
     *     id="invoice.subcategory_update",
     *     type="entity",
     *     class="CustomInvoiceBundle:InvoiceSubCategory",
     *     permission="EDIT"
     * )
     */
    public function updateAction(InvoiceSubCategory $subCategory, Request $request)
    {
        return $this->update($subCategory, $request);
    }

    private function update(InvoiceSubCategory $subCategory, Request $request)
    {
        $form = $this->get('form.factory')->create(InvoiceSubCategoryType::class, $subCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($subCategory);
            $entityManager->flush();

            return $this->redirectToRoute('invoice_subcategory_view', ['id' => $subCategory->getId()]);
        }

        return [
            'entity' => $subCategory,
            'form' => $form->createView(),
        ];
    }
}
