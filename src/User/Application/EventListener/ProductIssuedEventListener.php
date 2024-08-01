<?php

declare(strict_types=1);

namespace App\User\Application\EventListener;

use App\Product\Application\Event\ProductIssuedEvent;
use App\User\Application\Command\NotifyUserAboutIssuedProductCommand;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsEventListener]
readonly class ProductIssuedEventListener
{
    public function __construct(
        private MessageBusInterface $messageBus,
    ) {
    }

    public function __invoke(ProductIssuedEvent $event): void
    {
        $this->messageBus->dispatch(
            new NotifyUserAboutIssuedProductCommand(
                userId: $event->userId,
                productId: $event->productId
            )
        );
    }
}