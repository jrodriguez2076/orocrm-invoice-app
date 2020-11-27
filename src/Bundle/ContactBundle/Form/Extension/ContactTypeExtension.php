<?php

declare(strict_types=1);

namespace Custom\Bundle\ContactBundle\Form\Extension;

use Oro\Bundle\ContactBundle\Form\Type\ContactType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $builder
            ->add(
                'idDocument',
                TextType::class,
                [
                    'required' => true,
                    'label' => 'oro.contact.id_document.label',
                    'constraints' => new NotBlank(),
                    'empty_data' => null,
                    'is_dynamic_field' => true,
                ]
            );
    }

    public static function getExtendedTypes(): iterable
    {
        return [ContactType::class];
    }
}
