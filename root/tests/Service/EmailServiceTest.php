<?php

namespace App\Tests\Service;

use App\Entity\Client;
use App\Service\EmailService;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class EmailServiceTest extends TestCase
{
    private $emailService;
    private MailerInterface $mailer;

    public function setUp(): void
    {
        $this->mailer = $this->createMock(MailerInterface::class);
        $this->emailService = new EmailService($this->mailer);
    }

    public function testSendClientWelcomeEmailSendsCorrectEmail(): void
    {
        $client = new Client();
        $client->setEmail('test@client.com');

        $this->mailer->expects($this->once())
            ->method('send')
            ->with($this->callback(function (TemplatedEmail $email) use ($client) {
                $this->assertSame('Welcome!', $email->getSubject());
                $this->assertSame('mini@crm.pl', $email->getFrom()[0]->getAddress());
                $this->assertSame($client->getEmail(), $email->getTo()[0]->getAddress());
                $this->assertSame('emails/client_welcome.html.twig', $email->getHtmlTemplate());
                $this->assertArrayHasKey('clientName', $email->getContext());
                $this->assertSame($client->getEmail(), $email->getContext()['clientName']);

                return true;
            }));

        $this->emailService->sendClientWelcomeEmail($client);
    }
}
