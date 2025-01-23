<?php

declare(strict_types=1);

namespace App\Amqp\SSL;

use Hyperf\Amqp\IO\IOFactoryInterface;
use Hyperf\Amqp\Params;
use PhpAmqpLib\Wire\IO\AbstractIO;

class IOFactoryCustom implements IOFactoryInterface
{
    public function create(array $config, Params $params): AbstractIO
    {
        $host = $config['host'] ?? 'localhost';
        $port = $config['port'] ?? 5672;
        $openSsl = $config['ssl']['open_ssl'] ?? false;
        $sslOptions = $config['ssl'] ?? [];

        return new SwooleIOCustom(
            host: $host,
            port: $port,
            connectionTimeout: $params->getConnectionTimeout(),
            readWriteTimeout: $params->getReadWriteTimeout(),
            sslOptions: $sslOptions,
        );
    }
}
