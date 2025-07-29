<?php

namespace App\EventSubscriber;

use App\Event\ClientCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ClientCreatedSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            ClientCreatedEvent::class => 'onClientCreated',
        ];
    }

    public function onClientCreated(ClientCreatedEvent $event): void
    {
        $client = $event->getClient();
    }
}
