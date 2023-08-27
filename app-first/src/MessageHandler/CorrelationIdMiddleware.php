<?php

namespace App\MessageHandler;

use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpReceivedStamp;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\HandlerArgumentsStamp;

class CorrelationIdMiddleware implements MiddlewareInterface
{
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $correlationId = $envelope->last(AmqpReceivedStamp::class)->getAmqpEnvelope()->getCorrelationId();

        $envelope = $envelope->with(new HandlerArgumentsStamp([
            'correlationId' => $correlationId
        ]));

        return $stack->next()->handle($envelope, $stack);
    }
}