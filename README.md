The main objective of this repository is to show a scenario where the Hyperf framework is possibly experiencing an SSL issue during an attempt to connect to RabbitMQ. This repository will be used as a basis for a discussion in the Hyperf community to check if other users are also facing the same issue. Follow the instructions below to replicate the error in your development environment:

## How to reproduce the bug

See the environment specifications:

```shell
swoole

Swoole => enabled
Author => Swoole Team <team@swoole.com>
Version => 6.0.0
Built => Jan  2 2025 07:25:44
coroutine => enabled with boost asm context
epoll => enabled
eventfd => enabled
signalfd => enabled
spinlock => enabled
rwlock => enabled
openssl => OpenSSL 3.1.7 3 Sep 2024
dtls => enabled
http2 => enabled
json => enabled
curl-native => enabled
pcre => enabled
c-ares => 1.27.0
zlib => 1.3.1
brotli => E16781312/D16781312
mutex_timedlock => enabled
pthread_barrier => enabled
coroutine_pgsql => enabled
coroutine_odbc => enabled
coroutine_sqlite => enabled

Directive => Local Value => Master Value
swoole.enable_library => On => On
swoole.enable_fiber_mock => Off => Off
swoole.enable_preemptive_scheduler => Off => Off
swoole.display_errors => On => On
swoole.use_shortname => Off => Off
swoole.unixsock_buffer_size => 8388608 => 8388608
```

### Environment Preparation

Follow the instructions below to set up your environment according to the project specifications:

1. Run docker compose

```sh
docker compose up -d --build
```

2. Enter the container

```sh
docker exec -it $(docker ps -f name=hyperf-skeleton | grep "hyperf-skeleton" | awk '{ print $1 }') bash
```

3. Install dependencies

```sh
composer install
```

### Test scenario outside Hyperf

1. Run success test

We will execute a simple PHP code that establishes a connection with RabbitMQ using SSL. To do this, you should run the code below:

```sh
php examples/example-context.php
```

See below the output and log returned when executing the command:

PHP output:

```shell
user:/opt/www# php examples/example-context.php
string(22) "RabbitMQ channel ID: 1"
```

RabbitMQ log:

```log
[info] <0.888.0> accepting AMQP connection 192.168.224.3:51702 -> 192.168.224.2:5671
[info] <0.888.0> connection 192.168.224.3:51702 -> 192.168.224.2:5671: user 'guest' authenticated and granted access to vhost '/'
[info] <0.888.0> closing AMQP connection (192.168.224.3:51702 -> 192.168.224.2:5671, vhost: '/', user: 'guest', duration: '12ms')
```

By analyzing the image, we can see that the connection is being established correctly and, according to the RabbitMQ log, this connection was successfully accepted.

2. Run success test with coroutine

Run the command below to test an SSL connection using a coroutine:

```sh
php examples/example-context-coroutine.php
```

### Test scenario with Hyperf

Now, see an error scenario using Hyperf. To check this scenario, you should run the command below:

```sh
php bin/hyperf.php start
```

See below the output and log returned when executing the command:

Hyperf output:

```shell
NOTICE  Socket::ssl_connect(fd=19) to server[192.168.224.2:5671] failed. Error: error:0A000410:SSL routines::sslv3 alert handshake failure[1|1040]
[ERROR] PhpAmqpLib\Exception\AMQPRuntimeException: Error Connecting to server: Invalid argument  in /opt/www/vendor/hyperf/amqp/src/IO/SwooleIO.php:121
Stack trace:
#0 /opt/www/vendor/hyperf/amqp/src/IO/SwooleIO.php(65): Hyperf\Amqp\IO\SwooleIO->makeClient()
#1 /opt/www/vendor/php-amqplib/php-amqplib/PhpAmqpLib/Connection/AbstractConnection.php(253): Hyperf\Amqp\IO\SwooleIO->connect()
#2 /opt/www/vendor/php-amqplib/php-amqplib/PhpAmqpLib/Connection/AbstractConnection.php(235): PhpAmqpLib\Connection\AbstractConnection->connect()
#3 /opt/www/vendor/hyperf/amqp/src/AMQPConnection.php(79): PhpAmqpLib\Connection\AbstractConnection->__construct()
#4 /opt/www/vendor/hyperf/amqp/src/ConnectionFactory.php(101): Hyperf\Amqp\AMQPConnection->__construct()
#5 /opt/www/vendor/hyperf/amqp/src/ConnectionFactory.php(50): Hyperf\Amqp\ConnectionFactory->make()
#6 /opt/www/vendor/hyperf/amqp/src/ConnectionFactory.php(83): Hyperf\Amqp\ConnectionFactory->refresh()
#7 /opt/www/vendor/hyperf/amqp/src/Consumer.php(54): Hyperf\Amqp\ConnectionFactory->getConnection()
#8 /opt/www/vendor/hyperf/amqp/src/ConsumerManager.php(74): Hyperf\Amqp\Consumer->consume()
#9 /opt/www/vendor/hyperf/process/src/AbstractProcess.php(101): Hyperf\Process\AbstractProcess@anonymous->handle()
#10 [internal function]: Hyperf\Process\AbstractProcess->Hyperf\Process\{closure}()
#11 {main}
```

RabbitMQ log:

```log
[notice] <0.910.0> TLS server: In state certify at tls_dtls_server_connection.erl:145 generated SERVER ALERT: Fatal - Handshake Failure
[notice] <0.910.0>  - no_client_certificate_provided
```
