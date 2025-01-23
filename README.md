The main objective of this repository is to show a scenario where the Hyperf framework is possibly experiencing an SSL issue during an attempt to connect to RabbitMQ. This repository will be used as a basis for a discussion in the Hyperf community to check if other users are also facing the same issue. Follow the instructions below to replicate the error in your development environment:

## How to reproduce the bug

See the environment specifications:

```shell
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

[IMAGE]

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

[IMAGEM]
