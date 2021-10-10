<?php

require __DIR__ . '/../../vendor/autoload.php';

function dbConnect()
{
  $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../..'); 
	$dotenv->load();

  $dbHost = $_ENV['DB_HOST'];
  $dbUserName = $_ENV['DB_USERNAME'];
  $dbPassword = $_ENV['DB_PASSWORD'];
  $dbDatabase = $_ENV['DB_DATABASE'];

  $dbHost = $_ENV['DB_HOST'];
  $link = mysqli_connect($dbHost, $dbUserName, $dbPassword, $dbDatabase);

  if (!$link) {
    echo 'error:dbに接続できません'. PHP_EOL;
    echo 'error:'. mysqli_connect_error(). PHP_EOL;
    exit;
  }
  return $link;
}

