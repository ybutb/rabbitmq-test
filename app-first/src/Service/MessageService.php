<?php

declare(strict_types=1);

namespace App\Service;
use App\Message\AppMessage;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\MessageBusInterface;

class MessageService
{
    public function __construct(
        private readonly MessageBusInterface $bus,
        private readonly string $requestRoutingKey,
        private readonly string $responseRoutingKey
    )
    {
    }

    public function send(int $amount): void
    {
        $this->bus->dispatch(new AppMessage($amount), [
            new AmqpStamp($this->requestRoutingKey, AMQP_NOPARAM, [
                'correlation_id' => uniqid(),
                'reply_to' => $this->responseRoutingKey
            ])
        ]);
    }
}