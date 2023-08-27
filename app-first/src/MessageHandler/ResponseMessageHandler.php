<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\ResponseMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus:'response.bus', fromTransport: 'response_queue', handles: 'App\Message\ResponseMessage')]
class ResponseMessageHandler
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    public function __invoke(ResponseMessage $message, string $correlationId)
    {
        $this->logger->info('Response message received', [
            'correlation_id' => $correlationId,
            'value' => $message->getValue()
        ]);
    }
}
