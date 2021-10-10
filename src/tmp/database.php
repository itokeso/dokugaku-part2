<?php
  $link = mysqli_connect('db', 'book_log', 'pass', 'book_log');

  if (!$link) {
    echo 'データベースに接続できませんでした。'. PHP_EOL;
    echo 'error:'. mysqli_connect_error(). PHP_EOL;
    exit;
  }

  echo 'データベースに接続しました。'. PHP_EOL;

  $sql = 'SELECT name, founder FROM companies';
  $results = mysqli_query($link, $sql);

  while ($company = mysqli_fetch_assoc($results)) {
    echo '会社名:' . $company['name']. PHP_EOL;
    echo '代表者名:' . $company['founder'] . PHP_EOL;
  }

  mysqli_free_result($results);
  // $sql = <<<EOT
  // INSERT INTO companies (
  //   name,
  //   establishment_date,
  //   founder
  // ) VALUES (
  //   'smartHR',
  //   '2021-09-09',
  // )
  // EOT;


  // $result = mysqli_query($link, $sql);
  // if ($result) {
  //   echo 'データを追加しました'. PHP_EOL;
  // } else {
  //   echo '失敗しました.' . PHP_EOL;
  //   echo 'error:'. mysqli_error($link). PHP_EOL;
  // }

  mysqli_close($link);
  echo 'データベースとの接続を切断しました。'. PHP_EOL;

