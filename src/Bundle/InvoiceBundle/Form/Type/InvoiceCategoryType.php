<?php

declare(strict_types=1);

namespace Custom\Bundle\InvoiceBundle\Form\Type;

use Custom\Bundle\InvoiceBundle\Entity\InvoiceCategory;
use Oro\Bundle\FormBundle\Form\Type\CheckboxType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class InvoiceCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'required' => true,
                    'label' => 'oro.invoice.category.name.label',
                    'constraints' => new NotBlank(),
                ]
            )
            ->add(
                'invoiceFormEnabled',
                CheckboxType::class,
                [
                    'label' => 'oro.invoice.category.invoice_form_enabled.label',
                    'required' => false,
                    'is_dynamic_field' => true,
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InvoiceCategory::class,
        ]);
    }

    public function getName()
    {
        return 'invoice_category';
    }
}
