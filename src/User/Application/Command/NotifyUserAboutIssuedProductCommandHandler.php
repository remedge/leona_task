<?php

declare(strict_types=1);

namespace App\User\Application\Command;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class NotifyUserAboutIssuedProductCommandHandler
{
    public function __invoke(NotifyUserAboutIssuedProductCommand $notifyUserAboutIssuedProductCommand): void
    {
        // "*Notify user about issued product with sms*\n";
        // "*Notify user about issued product with email*\n";
    }
}