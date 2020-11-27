<?php

declare(strict_types=1);

namespace Custom\Bundle\InvoiceBundle\Form\Type;

use Custom\Bundle\InvoiceBundle\Entity\InvoiceCategory;
use Custom\Bundle\InvoiceBundle\Entity\InvoiceSubCategory;
use Doctrine\ORM\EntityRepository;
use Oro\Bundle\FormBundle\Form\Type\Select2EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class InvoiceSubCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'required' => true,
                    'label' => 'oro.invoice.subcategory.name.label',
                    'constraints' => new NotBlank(),
                    'is_dynamic_field' => true,
                ]
            )
            ->add('category', Select2EntityType::class, [
                'required' => true,
                'label' => 'oro.invoice.category.label',
                'class' => InvoiceCategory::class,
                'choice_label' => 'name',
                'constraints' => new NotBlank(),
                'configs' => [
                    'allowClear' => false,
                    'openOnEnter' => true,
                ],
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->groupBy('c.name')
                        ->orderBy('c.name', 'ASC');
                },
                'is_dynamic_field' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InvoiceSubCategory::class,
        ]);
    }

    public function getName()
    {
        return 'invoice_subcategory';
    }
}
