<?php
require('vendor/autoload.php');

use PhpAmqpLib\Connection\AMQPSSLConnection;
use function Swoole\Coroutine\run;

run(function () {
  
  $host = getenv('AMQP_HOST');
  $user = getenv('AMQP_USER');
  $pass = getenv('AMQP_PASSWORD');
  $vhost = '/';

  $ssl_opts = [
    'cafile' => '/opt/www/.docker/rabbitmq/certs/ca.pem',
    'local_cert' => '/opt/www/.docker/rabbitmq/certs/server-cert.pem',
    'local_pk' => '/opt/www/.docker/rabbitmq/certs/server-key.pem',
    'verify_peer' => true,
    'verify_peer_name' => false,
  ];

  $connection = new AMQPSSLConnection(
    host: $host, 
    port: 5671, 
    user: $user, 
    password: $pass, 
    vhost: $vhost, 
    ssl_options: $ssl_opts
  );

  $channel = $connection->channel();

  $channel->close();
  $connection->close();

});
