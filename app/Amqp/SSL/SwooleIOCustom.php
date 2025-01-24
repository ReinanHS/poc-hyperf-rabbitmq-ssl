<?php

declare(strict_types=1);

namespace App\Amqp\SSL;

use Hyperf\Amqp\IO\SwooleIO as IOSwooleIO;
use PhpAmqpLib\Exception\AMQPRuntimeException;
use Swoole\Coroutine\Socket;

class SwooleIOCustom extends IOSwooleIO
{
    public function __construct(
        string $host,
        int $port,
        protected int $connectionTimeout,
        protected int $readWriteTimeout = 3,
        protected array $sslOptions = [],
    ) {
        parent::__construct($host, $port, $connectionTimeout, $readWriteTimeout);
    }

    protected function makeClient(): Socket
    {
        $sock = new Socket(AF_INET, SOCK_STREAM, 0);

        if ($this->sslOptions['open_ssl'] === true) {
            $sock->setProtocol($this->sslOptions);
        }

        if (! $sock->connect($this->host, $this->port, $this->connectionTimeout)) {
            throw new AMQPRuntimeException(
                sprintf('Error Connecting to server: %s ', $sock->errMsg),
                $sock->errCode
            );
        }
        return $sock;
    }
}
