<?php

declare(strict_types=1);

namespace Custom\Bundle\InvoiceBundle\Form\Type;

use Custom\Bundle\InvoiceBundle\Entity\InvoiceFile;
use Custom\Bundle\InvoiceBundle\Form\DataTransformer\idToCategoryTransformer;
use Oro\Bundle\AttachmentBundle\Form\Type\FileType;
use Oro\Bundle\ContactBundle\Form\Type\ContactSelectType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class InvoiceFileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'file',
                FileType::class,
                [
                    'required' => true,
                    'label' => 'oro.invoice.file.label',
                    'constraints' => new NotBlank(),
                    'is_dynamic_field' => true,
                ]
            )
            ->add(
                'category',
                ChoiceType::class,
                [
                    'required' => true,
                    'placeholder' => 'oro.invoice.select_option.label',
                    'choices' => $options['categories'],
                    'label' => 'oro.invoice.category.label',
                    'constraints' => new NotBlank(),
                    'empty_data' => null,
                    'is_dynamic_field' => true,
                ]
            )
            ->add(
                'relatedContact',
                ContactSelectType::class,
                [
                    'required'               => true,
                    'label'                  => 'oro.invoice.related_contact.label',
                    'new_item_property_name' => 'firstName',
                    'configs'                => [
                        'renderedPropertyName'    => 'fullName',
                        'placeholder'             => 'oro.contact.form.choose_contact',
                        'result_template_twig'    => 'OroFormBundle:Autocomplete:fullName/result.html.twig',
                        'selection_template_twig' => 'OroFormBundle:Autocomplete:fullName/selection.html.twig'
                    ]
                ]
            );

        if ($options['api'] === false) {
            $builder->get('category')->addModelTransformer(new idToCategoryTransformer($options['entityManager']));
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InvoiceFile::class,
            'categories' => null,
            'entityManager' => null,
            'api' => true,
        ]);

        $resolver->setRequired('categories');
    }

    public function getName()
    {
        return 'invoice_file';
    }
}
