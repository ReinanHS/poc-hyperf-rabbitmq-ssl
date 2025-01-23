<?php

declare(strict_types=1);

namespace App\Command;

use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Command\Annotation\Command;
use Psr\Container\ContainerInterface;
use PhpAmqpLib\Connection\AMQPStreamConnection;

#[Command]
class Test extends HyperfCommand
{
    public function __construct(protected ContainerInterface $container)
    {
        parent::__construct('test');
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription('Hyperf Demo Command');
    }

    public function handle()
    {
        $host = getenv('AMQP_HOST');
        $user = getenv('AMQP_USER');
        $pass = getenv('AMQP_PASSWORD');
        $vhost = '/';

        $connection = new AMQPStreamConnection(
        host: $host, 
        port: 5671, 
        user: $user, 
        password: $pass, 
        vhost: $vhost,
        context: stream_context_create([
            'ssl' => [
                'cafile' => '/opt/www/.docker/rabbitmq/certs/ca.pem',
                'local_cert' => '/opt/www/.docker/rabbitmq/certs/server-cert.pem',
                'local_pk' => '/opt/www/.docker/rabbitmq/certs/server-key.pem',
                'verify_peer' => true,
                'verify_peer_name' => false,
            ],
        ]));

        $channel = $connection->channel();

        var_dump("RabbitMQ channel ID: ". $channel->getChannelId());

        $channel->close();
        $connection->close();

    }
}
