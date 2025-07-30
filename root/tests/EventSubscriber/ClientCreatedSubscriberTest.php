<?php

namespace App\Tests\EventSubscriber;

use App\Entity\Client;
use App\Event\ClientCreatedEvent;
use App\EventSubscriber\ClientCreatedSubscriber;
use App\Service\EmailService;
use PHPUnit\Framework\TestCase;

class ClientCreatedSubscriberTest extends TestCase
{
    private $emailService;

    public function setUp(): void
    {
        $this->emailService = $this->createMock(EmailService::class);
    }

    public function testEmailIsSentOnClientCreatedEvent(): void
    {
        $subscriber = new ClientCreatedSubscriber($this->emailService);

        $client = new Client();
        $client->setEmail('test@client.com');

        $this->emailService->expects($this->once())->method('sendClientWelcomeEmail')
            ->with($this->equalTo($client));

        $event = new ClientCreatedEvent($client);
        $subscriber->onClientCreated($event);
    }
}
