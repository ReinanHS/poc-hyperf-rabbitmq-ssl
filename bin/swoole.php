#!/usr/bin/env php
<?php

ini_set('display_errors', 'on');
ini_set('display_startup_errors', 'on');
ini_set('memory_limit', '1G');

error_reporting(E_ALL);
date_default_timezone_set('America/Sao_Paulo');

! defined('BASE_PATH') && define('BASE_PATH', dirname(__DIR__, 1));

require BASE_PATH . '/vendor/autoload.php';

use Swoole\Database\PDOPool;
use Swoole\Database\PDOConfig;
use Swoole\Runtime;

Swoole\Runtime::enableCoroutine();  // Enable coroutine support for native PHP clients

Swoole\Coroutine::create(function () {
  // PDO Config with SSL and timeout settings
  $pdoConfig = (new PDOConfig())
      ->withHost('mysql')
      ->withPort(3306)
      ->withDbName('userdb')
      ->withCharset('utf8mb4')
      ->withUsername('user_no_ssl')
      ->withPassword('user_no_ssl')
      ->withOptions([
          PDO::MYSQL_ATTR_SSL_CA    => '/opt/www/.docker/mysql/certs/ca.pem',
          PDO::MYSQL_ATTR_SSL_CERT  => '/opt/www/.docker/mysql/certs/server-cert.pem',
          PDO::MYSQL_ATTR_SSL_KEY   => '/opt/www/.docker/mysql/certs/server-key.pem',
          PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
          PDO::ATTR_TIMEOUT         => 5,  // Connection timeout
          PDO::ATTR_ERRMODE         => PDO::ERRMODE_EXCEPTION,
      ]);

  // Create a PDO connection pool
  $pool = new PDOPool($pdoConfig, 10);  // Pool size 10

  // Get a connection from the pool
  $pdo = $pool->get();

  if (!$pdo) {
      echo "Failed to get a connection from the pool.\n";
      return;
  }

  // Perform a query
  $statement = $pdo->query("SELECT VERSION()");
  $result = $statement->fetch();

  print_r($result);

  // Return the connection to the pool
  $pool->put($pdo);
});


Swoole\Coroutine::create(function () {
  // PDO Config with SSL and timeout settings
  $pdoConfig = (new PDOConfig())
      ->withHost('mysql')
      ->withPort(3306)
      ->withDbName('userdb')
      ->withCharset('utf8mb4')
      ->withUsername('user_ssl')
      ->withPassword('user_ssl')
      ->withOptions([
          PDO::MYSQL_ATTR_SSL_CA    => '/opt/www/.docker/mysql/certs/ca.pem',
          PDO::MYSQL_ATTR_SSL_CERT  => '/opt/www/.docker/mysql/certs/server-cert.pem',
          PDO::MYSQL_ATTR_SSL_KEY   => '/opt/www/.docker/mysql/certs/server-key.pem',
          PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
          PDO::ATTR_TIMEOUT         => 5,  // Connection timeout
          PDO::ATTR_ERRMODE         => PDO::ERRMODE_EXCEPTION,
      ]);

  // Create a PDO connection pool
  $pool = new PDOPool($pdoConfig, 10);  // Pool size 10

  // Get a connection from the pool
  $pdo = $pool->get();

  if (!$pdo) {
      echo "Failed to get a connection from the pool.\n";
      return;
  }

  // Perform a query
  $statement = $pdo->query("SELECT VERSION()");
  $result = $statement->fetch();

  print_r($result);

  // Return the connection to the pool
  $pool->put($pdo);
});
