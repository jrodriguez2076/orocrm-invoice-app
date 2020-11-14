<?php

declare(strict_types=1);

namespace Custom\Bundle\InvoiceBundle\Listener;

use Custom\Bundle\InvoiceBundle\Entity\InvoiceFile;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AutoAssignment implements EventSubscriber
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getSubscribedEvents()
    {
        return [
            'prePersist',
        ];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if (!$entity instanceof InvoiceFile) {
            return;
        }

        $this->assignRelatedAccount($entity);
    }

    protected function assignRelatedAccount(InvoiceFile $invoiceFile): void
    {
        $this->assignAccountByContact($invoiceFile);
    }

    public function assignAccountByContact(InvoiceFile $invoiceFile): void
    {
        if ($invoiceFile->getRelatedAccount()) {
            return;
        }

        $fileRelatedContact = $invoiceFile->getRelatedContact();

        if (!$fileRelatedContact) {
            return;
        }

        if (!$fileRelatedContact->hasAccounts()) {
            return;
        }

        $contactAccounts = $fileRelatedContact->getAccounts();

        $invoiceFile->setRelatedAccount($contactAccounts->first());
    }
}
