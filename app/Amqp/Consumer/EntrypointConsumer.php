<?php

declare(strict_types=1);

namespace App\Amqp\Consumer;

use Hyperf\Amqp\Annotation\Consumer;
use Hyperf\Amqp\Builder\QueueBuilder;
use Hyperf\Amqp\Message\ConsumerMessage;
use Hyperf\Amqp\Result;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;
use Psr\Log\LoggerInterface;

#[Consumer(exchange: 'hyperf', routingKey: 'hyperf', queue: 'hyperf', name: 'EntrypointConsumer')]
class EntrypointConsumer extends ConsumerMessage
{
    public function __construct(protected LoggerInterface $logger)
    {
    }

    public function consumeMessage(mixed $data, AMQPMessage $message): Result
    {
        $priority = $message->get_properties()['priority'] ?? 0;
        $this->logger->info(sprintf('O consumidor recebeu uma mensagem de prioridade %d', $priority));

        sleep(5);
        return Result::ACK;
    }

    public function getQueueBuilder(): QueueBuilder
    {
        return parent::getQueueBuilder()->setArguments(new AMQPTable([
            'x-ha-policy' => ['S', 'all'],
            'x-max-priority' => 10,
        ]));
    }
}
