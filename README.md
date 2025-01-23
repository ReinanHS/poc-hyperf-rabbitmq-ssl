## How to reproduce bug

1. Run docker compose

```sh
docker compose up -d --build
```

2. Enter container

```sh
docker exec -it $(docker ps -f name=hyperf-skeleton| grep "hyperf-skeleton" | awk '{ print $1 }') bash
```

3. Install depencies

```sh
composer install
```

4. Run success test

```sh
php examples/example-context.php
```

5. Run error test

```sh
php examples/example-context-coroutine.php
```
