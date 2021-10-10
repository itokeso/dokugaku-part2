<?php

function dbConnect()
{
  $link = mysqli_connect('db', 'book_log', 'pass', 'book_log');
  if (!$link) {
    echo 'error: dbに接続できませんでした。'. PHP_EOL;
    echo 'error:'. mysqli_connect_error(). PHP_EOL;
  }
  return $link;
}

function dropTable($link)
{
  $dropTableSql = 'DROP TABLE IF EXISTS reviews';
  $result = mysqli_query($link, $dropTableSql);
  if ($result) {
    echo 'テーブルを削除しました。'. PHP_EOL;
  } else {
    echo 'error: テーブルを削除できませんでした。'. PHP_EOL;
    echo 'error:'. mysqli_error($link). PHP_EOL;
  }
}

function createTable($link)
{
  $createTableSql = <<<EOT
  CREATE TABLE reviews (
    id INTEGER AUTO_INCREMENT NOT NULL PRIMARY KEY,
    title VARCHAR(255), 
    author VARCHAR(100),
    status VARCHAR(10),
    score INTEGER,
    summary VARCHAR(1000),
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
  ) DEFAULT CHARACTER SET=utf8mb4;
EOT;
  $result = mysqli_query($link, $createTableSql);
    if ($result) {
      echo 'テーブルを作成しました。'. PHP_EOL;
    } else {
      echo 'error: テーブルを作成できませんでした。'. PHP_EOL;
      echo 'error:'. mysqli_error($link). PHP_EOL;
    }
}

$link = dbConnect();
dropTable($link);
createTable($link);
mysqli_close($link);