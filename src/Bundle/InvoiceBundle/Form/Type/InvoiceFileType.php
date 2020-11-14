<?php

declare(strict_types=1);

namespace Custom\Bundle\InvoiceBundle\Form\Type;

use Custom\Bundle\InvoiceBundle\Entity\InvoiceFile;
use Oro\Bundle\AttachmentBundle\Form\Type\FileType;
use Oro\Bundle\ContactBundle\Form\Type\ContactSelectType;
use Symfony\Component\Form\AbstractType;
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
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InvoiceFile::class,
        ]);
    }

    public function getName()
    {
        return 'invoice_file';
    }
}
