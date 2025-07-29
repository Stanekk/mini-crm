<?php

namespace App\EventSubscriber;

use App\Event\ClientCreatedEvent;
use App\Service\EmailService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ClientCreatedSubscriber implements EventSubscriberInterface
{
    private EmailService $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ClientCreatedEvent::class => 'onClientCreated',
        ];
    }

    public function onClientCreated(ClientCreatedEvent $event): void
    {
        $client = $event->getClient();
        $this->emailService->sendClientWelcomeEmail($client);
    }
}
