<?php

declare(strict_types=1);

use App\Amqp\SSL\IOFactoryCustom;
use Hyperf\Amqp\IO\IOFactory;

use function Hyperf\Support\env;

return [
    'enable' => true,
    'default' => [
        'host' => env('AMQP_HOST', 'localhost'),
        'port' => 5671,
        'user' => env('AMQP_USER', 'guest'),
        'password' => env('AMQP_PASSWORD', 'guest'),
        'vhost' => env('AMQP_VHOST', '/'),
        'concurrent' => [
            'limit' => 2,
        ],
        'pool' => [
            'connections' => 2,
        ],
        'io' => IOFactoryCustom::class,
        'ssl' => [
            'open_ssl' => true,
            'ssl_cert_file' => '/opt/www/.docker/rabbitmq/certs/server-cert.pem',
            'ssl_key_file' => '/opt/www/.docker/rabbitmq/certs/server-key.pem',
        ],
        'params' => [
            'insist' => false,
            'login_method' => 'AMQPLAIN',
            'login_response' => null,
            'locale' => 'en_US',
            'connection_timeout' => 3,
            // Try to maintain twice value heartbeat as much as possible
            'read_write_timeout' => 6,
            // 'context' => stream_context_create([
            //     'ssl' => [
            //         'cafile' => '/opt/www/.docker/rabbitmq/certs/ca.pem',
            //         'local_cert' => '/opt/www/.docker/rabbitmq/certs/server-cert.pem',
            //         'local_pk' => '/opt/www/.docker/rabbitmq/certs/server-key.pem',
            //         'verify_peer' => true,
            //         'verify_peer_name' => false,
            //     ],
            // ]),
            'keepalive' => true,
            // Try to ensure that the consumption time of each message is less than the heartbeat time as much as possible
            'heartbeat' => 3,
            'channel_rpc_timeout' => 0.0,
            'close_on_destruct' => false,
            'max_idle_channels' => 10,
            'connection_name' => null,
        ],
    ],
];
