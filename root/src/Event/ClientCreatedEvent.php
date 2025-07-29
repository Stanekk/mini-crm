<?php

namespace App\Event;

use App\Entity\Client;
use Symfony\Contracts\EventDispatcher\Event;

class ClientCreatedEvent extends Event
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getClient(): Client
    {
        return $this->client;
    }
}
