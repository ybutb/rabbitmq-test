<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\AppMessage;
use App\Message\ResponseMessage;
use App\Service\MultiplierService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DispatchAfterCurrentBusStamp;

#[AsMessageHandler(bus: 'command.bus', handles: 'App\Message\AppMessage')]
class AppMessageHandler
{
    public function __construct(
        private readonly MultiplierService $service,
        private readonly MessageBusInterface $responseBus // wiring response.bus defined in messenger.yaml
    ) {
    }

    public function __invoke(AppMessage $message, string $correlationId, string $replyTo): void
    {
        $processedValue = $this->service->multiply($message->getValue());

        $this->responseBus->dispatch(new ResponseMessage($processedValue), [
            new AmqpStamp($replyTo, AMQP_NOPARAM, [
                'correlation_id' => $correlationId
            ]),
            new DispatchAfterCurrentBusStamp(),
        ]);
    }
}
