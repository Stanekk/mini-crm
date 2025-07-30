<?php

namespace App\Service;

use App\Entity\Client;
use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class EmailService
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    private function prepareBaseEmail(string $email, string $from, string $subject, string $templatePath): TemplatedEmail
    {
        $email = (new TemplatedEmail())->from($from)
            ->to(new Address($email))
            ->subject($subject)
            ->htmlTemplate($templatePath);

        return $email;
    }

    public function sendClientWelcomeEmail(Client $client): void
    {
        $preparedEmail = $this->prepareBaseEmail($client->getEmail(), 'mini@crm.pl', 'Welcome!', 'emails/client_welcome.html.twig');
        $preparedEmail->context(['clientName' => $client->getEmail()]);
        $this->mailer->send($preparedEmail);
    }

    public function sendUserWelcomeEmail(User $user): void
    {
        $preparedEmail = $this->prepareBaseEmail($user->getEmail(), 'mini@crm.pl', 'Welcome!', 'emails/user_welcome.html.twig');
        $preparedEmail->context(['userName' => $user->getEmail()]);
        $this->mailer->send($preparedEmail);
    }
}
