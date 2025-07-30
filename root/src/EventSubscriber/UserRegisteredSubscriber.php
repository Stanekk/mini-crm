<?php

namespace App\EventSubscriber;

use App\Event\UserRegisteredEvent;
use App\Service\EmailService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserRegisteredSubscriber implements EventSubscriberInterface
{
    private EmailService $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UserRegisteredEvent::class => 'onUserRegisteredEvent',
        ];
    }

    public function onUserRegisteredEvent(UserRegisteredEvent $event): void
    {
        $user = $event->getUser();
        $this->emailService->sendUserWelcomeEmail($user);
    }
}
