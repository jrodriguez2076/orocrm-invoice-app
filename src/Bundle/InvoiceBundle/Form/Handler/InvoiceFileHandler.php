<?php

declare(strict_types=1);

namespace Custom\Bundle\InvoiceBundle\Form\Handler;

use Doctrine\Persistence\ObjectManager;
use Custom\Bundle\InvoiceBundle\Event\Events;
use Custom\Bundle\InvoiceBundle\Event\FormHandlerEvent;
use Oro\Bundle\SoapBundle\Form\Handler\ApiFormHandler;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class InvoiceFileHandler extends ApiFormHandler
{
    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    public function __construct(
        FormInterface $form,
        RequestStack $requestStack,
        ObjectManager $manager,
        EventDispatcherInterface $dispatcher
    ) {
        parent::__construct($form, $requestStack, $manager);
        $this->dispatcher = $dispatcher;
    }

    /**
     * {@inheritdoc}
     */
    protected function onSuccess($entity): void
    {
        $this->entityManager->persist($entity);

        $this->dispatcher->dispatch(
            new FormHandlerEvent($this->form, $entity),
            Events::BEFORE_SAVE,
        );

        $this->entityManager->flush();
    }
}
