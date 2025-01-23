<?php

$pdo = new PDO(
    dsn: 'mysql:host=mysql;dbname=userdb',
    username: 'user_ssl',
    password: 'user_ssl',
    options: [
      PDO::MYSQL_ATTR_SSL_CA => "/opt/www/.docker/mysql/certs/ca.pem",
      PDO::MYSQL_ATTR_SSL_CERT => "/opt/www/.docker/mysql/certs/server-cert.pem",
      PDO::MYSQL_ATTR_SSL_KEY => "/opt/www/.docker/mysql/certs/server-key.pem",
      PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
  ],
);

$response = $pdo->query('show databases;');
var_dump($response->fetchAll());