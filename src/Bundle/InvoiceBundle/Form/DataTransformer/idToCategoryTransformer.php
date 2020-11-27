<?php

declare(strict_types=1);

namespace Custom\Bundle\InvoiceBundle\Form\DataTransformer;

use Custom\Bundle\InvoiceBundle\Entity\InvoiceSubCategory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;

class idToCategoryTransformer implements DataTransformerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function transform($value)
    {
        if (!$value) {
            return $value;
        }

        return $value->getId();
    }

    public function reverseTransform($value)
    {
        if (!is_string($value)) {
            return $value;
        }

        return $this->entityManager->getRepository(InvoiceSubCategory::class)->findOneBy(['id' => $value]);
    }
}
