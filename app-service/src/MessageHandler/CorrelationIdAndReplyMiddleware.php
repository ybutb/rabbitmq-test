<?php

namespace App\MessageHandler;

use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpReceivedStamp;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\HandlerArgumentsStamp;

class CorrelationIdAndReplyMiddleware implements MiddlewareInterface
{
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $correlationId = $envelope->last(AmqpReceivedStamp::class)->getAmqpEnvelope()->getCorrelationId();

        $envelope = $envelope->with(new HandlerArgumentsStamp([
            'correlationId' => $correlationId,
            'replyTo' => $envelope->last(AmqpReceivedStamp::class)->getAmqpEnvelope()->getReplyTo()
        ]));

        return $stack->next()->handle($envelope, $stack);
    }
}